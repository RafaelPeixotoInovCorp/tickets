<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactRole;
use App\Models\Entity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EntityController extends Controller
{
    public function index(): View
    {
        $entities = Entity::withCount('tickets', 'contacts')->orderBy('name')->paginate(20);

        return view('entities.index', compact('entities'));
    }

    public function create(): View
    {
        return view('entities.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nif' => ['nullable', 'string', 'max:50', 'unique:entities,nif'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'mobile' => ['nullable', 'string', 'max:50'],
            'website' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'internal_notes' => ['nullable', 'string'],
        ]);

        $entity = Entity::create($validated);

        return redirect()->route('entities.show', $entity)->with('success', 'Entidade criada.');
    }

    public function show(Entity $entity): View
    {
        $entity->load(['contacts.contactRole', 'tickets' => fn ($q) => $q->latest()->limit(10)]);

        return view('entities.show', compact('entity'));
    }

    public function edit(Entity $entity): View
    {
        return view('entities.edit', compact('entity'));
    }

    public function update(Request $request, Entity $entity): RedirectResponse
    {
        $validated = $request->validate([
            'nif' => ['nullable', 'string', 'max:50', 'unique:entities,nif,'.$entity->id],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'mobile' => ['nullable', 'string', 'max:50'],
            'website' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'internal_notes' => ['nullable', 'string'],
        ]);

        $entity->update($validated);

        return redirect()->route('entities.show', $entity)->with('success', 'Entidade atualizada.');
    }
}
