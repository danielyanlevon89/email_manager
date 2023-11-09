<?php

namespace App\Tables;

use App\Models\Category;
use Illuminate\Http\Request;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\Facades\Toast;
use ProtoneMedia\Splade\SpladeTable;

class Categories extends AbstractTable
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
        return Category::query();
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
            ->withGlobalSearch(columns: ['name', 'slug'])
            ->column('id', sortable: true)
            ->column('name', canBeHidden: false,sortable: true)
            ->column('slug')
        ->column('action', exportAs: false)
            ->export()
        ->bulkAction(
            label: __('Delete'),
                each: fn(Category $category) => $category->delete(),
                confirm: __('Delete Categories'),
                confirmText:  __('Are you sure?'),
                confirmButton: __('Yes'),
                cancelButton: __('Cancel'),
                after: fn() =>  Toast::title(__('Categories Deleted Successfuly'))->autoDismiss(2)
            )
            ->paginate(5);

            // ->searchInput()
            // ->selectFilter()
            // ->withGlobalSearch()

            // ->bulkAction()
            // ->export()
    }
}
