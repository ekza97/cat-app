<?php

namespace App\DataTables;

use App\Models\Soal;
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

class SoalDataTable extends DataTable
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
        ->addColumn('file',function($row){
            $file = '';
            if($row->file==null){
                $file .= '<a href="#" class="btn btn-sm btn-outline-secondary">Tidak Ada</a>';
            }else{
                $file .= '<a href="#" class="btn btn-sm btn-outline-secondary" onclick="viewFile(\''.$row->file.'\',\''.$row->file_type.'\')"><i class="bi bi-eye"></i> Lihat File</a>';
            }
            return $file;
        })
        ->addColumn('mapel_guru',function($row){
            $mapel_guru = '<p>'.$row->mapel->code.'-'.$row->mapel->description.'<br>'.$row->teachers->name.'</p>';
            return $mapel_guru;
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
            $action .= '<form method="post" action="' . route("questions.destroy", Crypt::encrypt($row->id)) . '"
                id="deleteSoal" style="display:inline" data-bs-toggle="tooltip"
                data-bs-placement="top" title="Hapus Data">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-outline-danger btn-sm btn-icon">
                    <i class="bi bi-trash"></i>
                </button>
            </form>';
            }
            return $action;
        })
        ->rawColumns(['file','soal','mapel_guru','action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Soal $model): QueryBuilder
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
                    ->setTableId('soal-table')
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
            Column::make('file')->title('File Soal')->width(100),
            Column::make('soal')->title('Soal'),
            Column::make('mapel_guru')->title('Mapel/Guru')->width('250'),
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
        return 'Soal_' . date('YmdHis');
    }
}