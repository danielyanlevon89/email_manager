<?php

namespace App\Tables;

use App\Enums\Boolean;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\Facades\Toast;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class Links extends AbstractTable
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
                    $query->orWhere('url','LIKE',"%{$value}%");
                });
            });
        });

        return QueryBuilder::for(Link::where('user_id',Auth::id()))
            ->defaultSort('id')
            ->allowedSorts(['id','url','is_active'])
            ->allowedFilters([
                AllowedFilter::exact('is_active'),
                $globalSearch
            ]);
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
            ->column('id', sortable: true, hidden: true)
            ->column('url', canBeHidden: false,sortable: true)
            ->column('is_active', sortable: true,  as: fn ($is_active) => ($is_active) ? Boolean::true : Boolean::false)
            ->column('action', exportAs: false,alignment: 'right')
            ->selectFilter('is_active',options: Boolean::toArray())
            ->export()
            ->bulkAction(
                label: __('Delete'),
                each: fn(Link $link) => $link->delete(),
                confirm: __('Delete Link(s)'),
                confirmText:  __('Are you sure?'),
                confirmButton: __('Yes'),
                cancelButton: __('Cancel'),
                after: fn() =>  Toast::title(__('Link(s) Deleted Successfully'))->autoDismiss(2)
            )
            ->paginate(10);
    }
}
