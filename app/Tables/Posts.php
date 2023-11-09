<?php

namespace App\Tables;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\Facades\Toast;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class Posts extends AbstractTable
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
        return Post::query();
    }

    /**
     * Configure the given SpladeTable.
     *
     * @param \ProtoneMedia\Splade\SpladeTable $table
     * @return void
     */
    public function configure(SpladeTable $table)
    {

        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        ->orWhere('title', 'LIKE', "%{$value}%")
                        ->orWhere('slug', 'LIKE', "%{$value}%");
                });
            });
        });

        $posts = QueryBuilder::for (Post::class)
            ->defaultSort('title')
            ->allowedSorts(['title', 'slug'])
            ->allowedFilters(['title', 'slug', AllowedFilter::exact('category_id'), $globalSearch]);

        $categories = Category::pluck('name', 'id')->toArray();

        $table
            ->column('id', sortable: true)
            ->column('title', canBeHidden: false,sortable: true)
            ->withGlobalSearch(columns: ['title', 'slug'])
            ->selectFilter('category_id', $categories)
        ->column('slug', sortable: true)
            ->column('action', exportAs: false)
            ->export()
        ->bulkAction(
            label: __('Delete'),
                    each: fn(Post $post) => $post->delete(),
                    confirm: __('Delete Posts'),
                    confirmText:  __('Are you sure?'),
                    confirmButton: __('Yes'),
                    cancelButton: __('Cancel'),
                    after: fn() =>  Toast::title(__('Posts Deleted Successfuly'))->autoDismiss(2)
                )
           ->paginate(10);
            // ->searchInput()
            // ->selectFilter()
            // ->withGlobalSearch()

            // ->bulkAction()
            // ->export()
    }
}
