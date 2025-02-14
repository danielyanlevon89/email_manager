<?php

namespace App\Tables;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\Facades\Toast;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\Permission\Models\Permission;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EmailTemplates extends AbstractTable
{
    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the user is authorized to perform bulk actions and exports.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        return true;
    }

    /**
     * The resource or query builder.
     *
     * @return mixed
     */
    public function for()
    {
        $globalSearch = AllowedFilter::callback('global',function($query,$value){
            $query->where(function ($query) use ($value){
                Collection::wrap($value)->each(function ($value) use ($query){
                    $query->orWhere('name','LIKE',"%{$value}%");
                    $query->orWhere('text','LIKE',"%{$value}%");
                });
            });
        });

        return QueryBuilder::for(EmailTemplate::where('user_id',Auth::id()))
            ->defaultSort('id')
            ->allowedSorts(['id','name'])
            ->allowedFilters(['id','name',$globalSearch]);
    }

    /**
     * Configure the given SpladeTable.
     *
     * @param \ProtoneMedia\Splade\SpladeTable $table
     * @return void
     */
    public function configure(SpladeTable $table)
    {
        $table
            ->withGlobalSearch(columns: ['name'])
            ->column('id', sortable: true)
            ->column('name', canBeHidden: false,sortable: true)
            ->column('action', exportAs: false,alignment: 'right')
            ->export()
            ->bulkAction(
                label: __('Delete'),
                each: fn(EmailTemplate $template) => $template->delete(),
                confirm: __('Delete Template(s)'),
                confirmText:  __('Are you sure?'),
                confirmButton: __('Yes'),
                cancelButton: __('Cancel'),
                after: fn() =>  Toast::title(__('Template(s) Deleted Successfully'))->autoDismiss(2)
            )
            ->paginate(10);
    }
}
