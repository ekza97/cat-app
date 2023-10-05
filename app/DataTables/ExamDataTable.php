<?php

namespace App\DataTables;

use App\Models\Exam;
use App\Helpers\Helper;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ExamDataTable extends DataTable
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
            $name = '<p>'.$row->name.'<br>Token : <strong>'.$row->token.'</strong></p>';
            return $name;
        })
        ->addColumn('mapel',function($row){
            $mapel = '<p>'.$row->mapel->description.'<br>'.$row->teachers->name.'</p>';
            return $mapel;
        })
        ->addColumn('waktu',function($row){
            $waktu = '<p>'.Helper::dateIndo($row->exam_start).' ('.$row->jml_waktu.' Menit )</p>';
            return $waktu;
        })
        ->addColumn('exam_type',function($row){
            return $row->type=="acak"?'Soal diacak':'Soal diurut';
        })
        ->addColumn('action', function($row){
            $action = '';
            if(Gate::allows('edit user')){
                $action .= '<a href="#" onclick="editData(' . $row->id . ')" class="btn btn-sm btn-icon btn-outline-primary" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Edit Data">
                    <i class="bi bi-pencil-square"></i>
                </a>';
            }
            if(Gate::allows('delete user')){
            $action .= '<form method="post" action="' . route("exams.destroy", Crypt::encrypt($row->id)) . '"
                id="deleteExam" style="display:inline" data-bs-toggle="tooltip"
                data-bs-placement="top" title="Hapus Data">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-outline-danger btn-sm btn-icon">
                    <i class="bi bi-trash"></i>
                </button>
            </form>';
            }
            return $action;
        })
        ->rawColumns(['name','mapel','waktu','exam_type','action']);
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
                    ->setTableId('exam-table')
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
            Column::make('exam_type')->title('Pengacakan Soal'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(80)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Exam_' . date('YmdHis');
    }
}