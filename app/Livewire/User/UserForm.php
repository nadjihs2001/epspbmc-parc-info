<?php

namespace App\Livewire\User;

use App\Livewire\Concerns\AuthorizesModuleAccess;
use App\Models\Department;
use App\Models\Structure;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserForm extends Component
{
    use AuthorizesModuleAccess;

    public ?int $userId = null;

    public array $form = [
        'name' => '',
        'email' => '',
        'password' => '',
        'password_confirmation' => '',
        'structure_id' => null,
        'department_id' => null,
        'fonction' => '',
        'telephone' => '',
        'actif' => true,
        'role' => '',
    ];

    public function mount(?int $userId = null): void
    {
        $this->userId = $userId;
        $this->form['structure_id'] = $this->scopedStructureId();

        if ($userId) {
            $user = User::query()->with('roles')->findOrFail($userId);
            $this->authorizeRecordAccess($user, 'users.update');

            $this->form = array_merge($this->form, [
                ...$user->only([
                    'name',
                    'email',
                    'structure_id',
                    'department_id',
                    'fonction',
                    'telephone',
                    'actif',
                ]),
                'role' => $user->roles->first()?->name ?? '',
            ]);

            return;
        }

        $this->authorizePermission('users.create');
    }

    public function save(): void
    {
        $user = $this->userId ? User::findOrFail($this->userId) : null;

        if ($user) {
            $this->authorizeRecordAccess($user, 'users.update');
        } else {
            $this->authorizePermission('users.create');
        }

        $this->form = $this->lockStructureToAuthenticatedUser($this->form);

        $rules = [
            'form.name' => 'required|string|max:255',
            'form.email' => ['required', 'email', 'max:191', Rule::unique('users', 'email')->ignore($this->userId)],
            'form.structure_id' => 'required|exists:structures,id',
            'form.department_id' => [
                'nullable',
                Rule::exists('departments', 'id')->where(fn ($query) => $query->where('structure_id', $this->form['structure_id'])),
            ],
            'form.fonction' => 'nullable|string|max:255',
            'form.telephone' => 'nullable|string|max:50',
            'form.actif' => 'boolean',
            'form.role' => 'required|exists:roles,name',
        ];

        if ($this->userId) {
            $rules['form.password'] = ['nullable', Password::defaults()];
            $rules['form.password_confirmation'] = ['nullable', 'same:form.password'];
        } else {
            $rules['form.password'] = ['required', Password::defaults()];
            $rules['form.password_confirmation'] = ['required', 'same:form.password'];
        }

        $this->validate($rules, [], [
            'form.password' => 'mot de passe',
            'form.password_confirmation' => 'confirmation du mot de passe',
        ]);

        $payload = collect($this->form)
            ->except(['password', 'password_confirmation', 'role'])
            ->toArray();

        if (!empty($this->form['password'])) {
            $payload['password'] = Hash::make($this->form['password']);
        }

        $savedUser = User::updateOrCreate(
            ['id' => $this->userId],
            $payload
        );

        $savedUser->syncRoles([$this->form['role']]);

        $this->dispatch('notify', message: 'Utilisateur enregistre');
        $this->redirect(route('users.index', [], false));
    }

    public function render()
    {
        $scopedStructureId = $this->scopedStructureId();

        return view('livewire.user.form', [
            'structures' => Structure::query()
                ->when($scopedStructureId, fn ($query, $structureId) => $query->where('id', $structureId))
                ->orderBy('nom')
                ->get(),
            'departments' => Department::query()
                ->when($this->form['structure_id'], fn ($query, $structureId) => $query->where('structure_id', $structureId))
                ->orderBy('nom')
                ->get(),
            'roles' => Role::query()->orderBy('name')->get(),
        ])->layout('layouts.app');
    }
}
