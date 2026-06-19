<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Contact;
use App\Models\ContactRole;
use App\Models\Entity;
use App\Models\Inbox;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'Aberto', 'color' => '#22c55e', 'is_closed' => false, 'sort_order' => 1],
            ['name' => 'Em Tratamento', 'color' => '#3b82f6', 'is_closed' => false, 'sort_order' => 2],
            ['name' => 'Pendente Cliente', 'color' => '#f59e0b', 'is_closed' => false, 'sort_order' => 3],
            ['name' => 'Fechado', 'color' => '#6b7280', 'is_closed' => true, 'sort_order' => 4],
        ];

        foreach ($statuses as $status) {
            TicketStatus::create($status);
        }

        $roles = ['Diretor', 'Gestor', 'Técnico', 'Administrativo', 'Comercial'];
        foreach ($roles as $role) {
            ContactRole::create(['name' => $role]);
        }

        $inboxes = [
            ['name' => 'Comercial', 'slug' => 'comercial', 'color' => '#8b5cf6', 'description' => 'Pedidos e vendas'],
            ['name' => 'Apoio Técnico', 'slug' => 'apoio-tecnico', 'color' => '#3b82f6', 'description' => 'Suporte técnico'],
            ['name' => 'Recursos Humanos', 'slug' => 'recursos-humanos', 'color' => '#ec4899', 'description' => 'RH e colaboradores'],
        ];

        foreach ($inboxes as $inboxData) {
            $inbox = Inbox::create($inboxData);

            $types = match ($inbox->slug) {
                'comercial' => ['Proposta', 'Informação', 'Reclamação'],
                'apoio-tecnico' => ['Bug', 'Suporte', 'Instalação'],
                'recursos-humanos' => ['Recrutamento', 'Formação', 'Benefícios'],
                default => ['Geral'],
            };

            foreach ($types as $type) {
                TicketType::create(['inbox_id' => $inbox->id, 'name' => $type]);
            }
        }

        $operator = User::create([
            'name' => 'Operador Admin',
            'email' => 'operador@tickets.test',
            'password' => Hash::make('password'),
            'role' => UserRole::Operator,
        ]);

        $operator2 = User::create([
            'name' => 'Maria Silva',
            'email' => 'maria@tickets.test',
            'password' => Hash::make('password'),
            'role' => UserRole::Operator,
        ]);

        $allInboxes = Inbox::all();
        $operator->inboxes()->attach($allInboxes->pluck('id'));
        $operator2->inboxes()->attach([
            Inbox::where('slug', 'comercial')->first()->id,
            Inbox::where('slug', 'apoio-tecnico')->first()->id,
        ]);

        $entity = Entity::create([
            'nif' => '500123456',
            'name' => 'Empresa Exemplo Lda',
            'phone' => '210000000',
            'mobile' => '910000000',
            'website' => 'https://exemplo.pt',
            'email' => 'geral@exemplo.pt',
            'internal_notes' => 'Cliente prioritário',
        ]);

        $contact = Contact::create([
            'name' => 'João Cliente',
            'contact_role_id' => ContactRole::where('name', 'Diretor')->first()->id,
            'email' => 'cliente@tickets.test',
            'phone' => '210000001',
            'mobile' => '910000001',
        ]);

        $contact->entities()->attach($entity->id);

        $clientUser = User::create([
            'name' => 'João Cliente',
            'email' => 'cliente@tickets.test',
            'password' => Hash::make('password'),
            'role' => UserRole::Client,
            'contact_id' => $contact->id,
        ]);

        $comercialInbox = Inbox::where('slug', 'comercial')->first();
        $openStatus = TicketStatus::where('name', 'Aberto')->first();
        $type = TicketType::where('inbox_id', $comercialInbox->id)->first();

        Ticket::create([
            'number' => 'TC-1',
            'subject' => 'Pedido de informação sobre produtos',
            'ticket_type_id' => $type->id,
            'cc_emails' => ['comercial@exemplo.pt'],
            'ticket_status_id' => $openStatus->id,
            'operator_id' => $operator->id,
            'entity_id' => $entity->id,
            'contact_id' => $contact->id,
            'inbox_id' => $comercialInbox->id,
            'body' => 'Gostaria de receber informação detalhada sobre os produtos disponíveis e condições comerciais.',
        ]);
    }
}
