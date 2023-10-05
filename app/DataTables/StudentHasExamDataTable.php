<?php

namespace App\DataTables;

use App\Models\Exam;
use App\Helpers\Helper;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class StudentHasExamDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->addIndexColumn()
        ->addColumn('name',function($row){
            $name = '<p>'.$row->name.'</p>';
            return $name;
        })
        ->addColumn('mapel',function($row){
            $mapel = '<p>'.$row->mapel->description.'<br>'.$row->teachers->name.'</p>';
            return $mapel;
        })
        ->addColumn('waktu',function($row){
            $waktu = '<p>'.$row->jml_waktu.' Menit</p>';
            return $waktu;
        })
        ->addColumn('status',function($row){
            $cek = Helper::checkExam($row->id,Auth::id());
            return $cek>0?'Sudah Ikut':'Belum Ikut';
        })
        ->addColumn('action', function($row){
            $action = '';
            if(Helper::checkExam($row->id,Auth::id())>0){
                $action .= '<a href="#" onclick="editData(' . $row->id . ')" class="btn btn-sm btn-icon btn-outline-danger" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Anda telah mengikuti ujian ini">
                    <i class="bi bi-check-lg"></i>
                    Sudah Ikut
                </a>';
            }else{
                $action .= '<a href="'.route('exam-students.create').'" class="btn btn-sm btn-icon btn-outline-primary" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Klik untuk mengikuti ujian">
                    <i class="bi bi-pencil-square"></i>
                    Ikuti
                </a>';
            }
            return $action;
        })
        ->rawColumns(['name','mapel','waktu','action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Exam $model): QueryBuilder
    {
        // return $model->newQuery();
        $query = $model->query()->orderBy('id','desc');
        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('studenthasexam-table')
                    ->addTableClass('table-hover')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('No')->width(20)->searchable(false)->orderable(false),
            Column::make('name')->title('Nama Tes'),
            Column::make('mapel')->title('Mata Pelajaran'),
            Column::make('jml_soal')->title('Jumlah Soal'),
            Column::make('waktu'),
            Column::make('status')->title('Status'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'StudentHasExam_' . date('YmdHis');
    }
}