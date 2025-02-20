<?php

namespace App\Services;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\PasswordValidator;
use ProtoneMedia\Splade\Table\BulkAction;

class SpladeTableBulkActionService
{

    public function __invoke(Request $request, $table, $action, PasswordValidator $passwordValidator): RedirectResponse
    {
        $request->validate([
            'ids' => ['required', 'array', 'min:1'],
        ]);

        $action = base64_decode($action);

        /** @var AbstractTable $tableInstance */
        $tableInstance = app(base64_decode($table));

        if (!$tableInstance->authorize($request)) {
            throw new UnauthorizedException;
        }

        /** @var BulkAction */
        $bulkActionInstance = $tableInstance->make()->getBulkActions()[$action];

        if ($bulkActionInstance->requirePassword) {
            $passwordValidator->validateRequest($request, $bulkActionInstance->requirePassword);
        }

        $response = $tableInstance->performBulkAction($action, $request->input('ids', []));

        if ($response instanceof redirectResponse) {
            return $response;
        }

        return redirect()->back();
    }

}
