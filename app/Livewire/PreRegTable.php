<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\PreRegisteredPatient;

class PreRegTable extends DataTableComponent
{
    protected $model = PreRegisteredPatient::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('created_at', 'desc')
            ->setRefreshTime(60000) // Component refreshes every 60 seconds
            ->setPerPageAccepted([10, 25, 50, 100, -1]); // Options for pagination
    }

    public function columns(): array
    {
        return [
            Column::make("Code", "pre_registration_code")
                ->sortable()
                ->searchable(),

            Column::make("First Name", "first_name")
                ->sortable()
                ->searchable(),

            // Column::make("Middle Name", "middle_name")
            //     ->sortable()
            //     ->searchable(),

            // Column::make("Last Name", "last_name")
            //     ->sortable()
            //     ->searchable(),

            Column::make("Birthdate", "birthdate")
                ->sortable()
                ->searchable(),

            Column::make("Sex", "sex")
                ->sortable()
                ->searchable(),

            Column::make('Action')
                ->label(
                    fn($row, Column $column) => view('components.livewire.datatables.action-column')->with(
                        [
                            'viewLink' => route('dashboard', $row),
                            'editLink' => route('dashboard', $row),
                            'deleteLink' => route('dashboard', $row),
                        ]
                    )
                )->html(),
        ];
    }
}