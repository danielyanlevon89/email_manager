<?php

namespace App\Tables;


use App\Enums\Boolean;
use App\Http\Controllers\IncomingEmailsController;
use App\Models\EmailAddress;
use App\Models\IncomingEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\Facades\Toast;
use ProtoneMedia\Splade\SpladeQueryBuilder;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\Table\BulkAction;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IncomingEmails extends AbstractTable
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
                    $query->orWhere('from','LIKE',"%{$value}%");
                    $query->orWhere('to','LIKE',"%{$value}%");
                    $query->orWhere('subject','LIKE',"%{$value}%");
                    $query->orWhere('cc','LIKE',"%{$value}%");
                    $query->orWhere('reply_message_id','LIKE',"%{$value}%");
                    $query->orWhere('message_id','LIKE',"%{$value}%");
                });
            });
        });

        return QueryBuilder::for(IncomingEmail::where('user_id',Auth::id())
            ->where('email_accounts_id',session()->get('chosen_email_account','0'))
            ->select('*',
                DB::raw("(CASE WHEN seen_at is null THEN true ELSE false END) as new_message"),
            DB::raw("(CASE WHEN reply_message_id != '' THEN true ELSE false END) as replied_message")
            ))
            ->defaultSort('-id')
            ->allowedSorts(['id','message_id','reply_message_id','from','to','subject','cc','email_date','seen_at','has_attachment'])
            ->allowedFilters([
                AllowedFilter::exact('has_attachment'),
                $globalSearch
            ]);
    }

    /**
     * Performs the bulk action on the given ids.
     *
     * @return void
     */
    public function performBulkAction(int $key, array $ids)
    {
        $table = $this->make();

        if ($table instanceof SpladeQueryBuilder) {
            /** @var BulkAction $bulkAction */
            $bulkAction = $table->getBulkActions()[$key];

            if ($bulkAction->beforeCallback) {
                return call_user_func($bulkAction->beforeCallback, $ids);
            }

            if ($bulkAction->eachCallback) {
                $table->performBulkAction($bulkAction->eachCallback, $ids);
            }

            if ($bulkAction->afterCallback) {
                call_user_func($bulkAction->afterCallback, $ids);
            }
        }
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
            ->withGlobalSearch()
            ->column('id', sortable: true, hidden: true)
            ->column('message_id', sortable: true, hidden: true)
            ->column('reply_message_id', sortable: true, hidden: true)
            ->column('from', canBeHidden: false,sortable: true)
            ->column('to', sortable: true,hidden: true)
            ->column('cc', sortable: true, hidden: true)
            ->column('subject', sortable: true)
            ->column('email_date', sortable: true)
            ->column('seen_at', sortable: true)
            ->column('has_attachment', hidden: true, sortable: true, as: fn ($is_active) => ($is_active) ? Boolean::true : Boolean::false)
            ->rowModal(fn (IncomingEmail $incomingEmail) => route('incoming_email_details.show', $incomingEmail->id))
            ->export()
            ->bulkAction(
                label: __('Send Bulk Emails'),
                before: function ($ids) {

                    return redirect()->route('incoming_emails.index', [
                        'ids' => join(',', $ids)
                    ]);

                }
            )
            ->bulkAction(
                label: __('Set As Seen'),
                each: fn(IncomingEmail $incomingEmail) => (new IncomingEmailsController)->setAsSeen($incomingEmail),
                after: fn() =>  Toast::title(__('Emails Set As Seen'))->autoDismiss(2)
            )
            ->paginate(10);
    }
}
