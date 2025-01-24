<?php

namespace App\Tables;

use App\Enums\Boolean;
use App\Enums\Encryption;
use App\Models\EmailAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\Facades\Toast;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EmailAccounts extends AbstractTable
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
                    $query->orWhere('imap_host','LIKE',"%{$value}%");
                    $query->orWhere('imap_port','LIKE',"%{$value}%");
                    $query->orWhere('imap_username','LIKE',"%{$value}%");

                    $query->orWhere('smtp_host','LIKE',"%{$value}%");
                    $query->orWhere('smtp_port','LIKE',"%{$value}%");
                    $query->orWhere('smtp_username','LIKE',"%{$value}%");

                    $query->orWhere('email_address','LIKE',"%{$value}%");
                    $query->orWhere('email_from','LIKE',"%{$value}%");
                });
            });
        });

        return  QueryBuilder::for(EmailAccount::where('user_id',Auth::id()))
            ->defaultSort('id')
            ->allowedSorts([
                'id',
                'name',

                'imap_host',
                'imap_port',
                'imap_encryption',
                'imap_username',
                'imap_password',
                'imap_scan_days_count',
                'imap_result_limit',
                'is_active',
                'imap_result_limit',
                'imap_last_execute_time',
                'imap_last_execute_items_count',

                'smtp_host',
                'smtp_port',
                'smtp_send_email_count_in_minute',
                'smtp_last_execute_time',
                'smtp_last_execute_items_count',
                'smtp_encryption',
                'smtp_username',
                'smtp_password',
                'email_address',
                'email_from',
            ])
            ->allowedFilters([
                AllowedFilter::exact('is_active'),
                AllowedFilter::exact('imap_encryption'),
                AllowedFilter::exact('smtp_encryption'),
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
            ->column('name', canBeHidden: false,sortable: true,label: __('Account Name') )
            ->column('email_address', sortable: true )
            ->column('email_from', sortable: true)
            ->column('is_active', sortable: true,  as: fn ($is_active) => ($is_active) ? Boolean::true : Boolean::false)

            ->column('imap_host', sortable: true, hidden: true)
            ->column('imap_port', sortable: true, hidden: true)
            ->column('imap_encryption', sortable: true, hidden: true)
            ->column('imap_username', sortable: true, hidden: true)
            ->column('imap_password', sortable: true, hidden: true)
            ->column('imap_last_execute_time', sortable: true, hidden: true)
            ->column('imap_last_execute_items_count', sortable: true, hidden: true)
            ->column('imap_scan_days_count', sortable: true, hidden: true)
            ->column('imap_result_limit', sortable: true, hidden: true)

            ->column('smtp_host', sortable: true, hidden: true)
            ->column('smtp_port', sortable: true, hidden: true)
            ->column('smtp_encryption', sortable: true, hidden: true)
            ->column('smtp_username', sortable: true, hidden: true)
            ->column('smtp_password', sortable: true, hidden: true)
            ->column('smtp_last_execute_time', sortable: true, hidden: true)
            ->column('smtp_last_execute_items_count', sortable: true, hidden: true)
            ->column('smtp_send_email_count_in_minute', sortable: true, hidden: true)

            ->column('action', exportAs: false,alignment: 'right')
            ->selectFilter('imap_encryption',Encryption::toArray())
            ->selectFilter('is_active',options: Boolean::toArray())
            ->selectFilter('smtp_encryption',Encryption::toArray())
            ->export()
            ->paginate(10);

    }
}
