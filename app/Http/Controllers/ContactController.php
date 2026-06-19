<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactRole;
use App\Models\Entity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        $contacts = Contact::with(['contactRole', 'entities'])->orderBy('name')->paginate(20);

        return view('contacts.index', compact('contacts'));
    }

    public function create(): View
    {
        $roles = ContactRole::orderBy('name')->get();
        $entities = Entity::orderBy('name')->get();

        return view('contacts.create', compact('roles', 'entities'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact_role_id' => ['nullable', 'exists:contact_roles,id'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'mobile' => ['nullable', 'string', 'max:50'],
            'internal_notes' => ['nullable', 'string'],
            'entity_ids' => ['nullable', 'array'],
            'entity_ids.*' => ['exists:entities,id'],
        ]);

        $contact = Contact::create($validated);

        if (! empty($validated['entity_ids'])) {
            $contact->entities()->sync($validated['entity_ids']);
        }

        return redirect()->route('contacts.show', $contact)->with('success', 'Contacto criado.');
    }

    public function show(Contact $contact): View
    {
        $contact->load(['contactRole', 'entities', 'tickets' => fn ($q) => $q->latest()->limit(10)]);

        return view('contacts.show', compact('contact'));
    }

    public function edit(Contact $contact): View
    {
        $roles = ContactRole::orderBy('name')->get();
        $entities = Entity::orderBy('name')->get();

        return view('contacts.edit', compact('contact', 'roles', 'entities'));
    }

    public function update(Request $request, Contact $contact): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact_role_id' => ['nullable', 'exists:contact_roles,id'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'mobile' => ['nullable', 'string', 'max:50'],
            'internal_notes' => ['nullable', 'string'],
            'entity_ids' => ['nullable', 'array'],
            'entity_ids.*' => ['exists:entities,id'],
        ]);

        $contact->update($validated);
        $contact->entities()->sync($validated['entity_ids'] ?? []);

        return redirect()->route('contacts.show', $contact)->with('success', 'Contacto atualizado.');
    }
}
