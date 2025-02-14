<?php

namespace App\Tables;


use App\Models\BlackList;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\Facades\Toast;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BlackLists extends AbstractTable
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
                    $query->orWhere('word','LIKE',"%{$value}%");
                });
            });
        });

        return QueryBuilder::for(BlackList::where('user_id',Auth::id()))
            ->defaultSort('id')
            ->allowedSorts(['id','word'])
            ->allowedFilters(['id','word',$globalSearch]);
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
            ->column('id', sortable: true,hidden: true)
            ->column('word', canBeHidden: false,sortable: true)
            ->column('action', exportAs: false,alignment: 'right')
            ->export()
            ->bulkAction(
                label: __('Delete'),
                each: fn(BlackList $blackList) => $blackList->delete(),
                confirm: __('Delete Black List Item(s)'),
                confirmText:  __('Are you sure?'),
                confirmButton: __('Yes'),
                cancelButton: __('Cancel'),
                after: fn() =>  Toast::title(__('Black List Item(s) Deleted Successfully'))->autoDismiss(2)
            )
            ->paginate(10);
    }
}
