<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display the staff index page.
     *
     * @param string $locale
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(string $locale, Request $request): \Illuminate\View\View
    {
        $dashboardBlocks = [];

        $dashboardBlocks[] = [
            'link' => route('staff.data.list', ['name' => 'account']),
            'icon' => 'user-circle',
            'title' => trans('common.account_settings'),
            'desc' => trans('common.staffDashboardBlocks.account_settings')
        ];

        $dashboardBlocks[] = [
            'link' => route('staff.qr.scanner'),
            'icon' => 'qr-code',
            'title' => trans('common.scan_qr'),
            'desc' => trans('common.staffDashboardBlocks.scan_qr')
        ];

        $dashboardBlocks[] = [
            'link' => route('staff.data.list', ['name' => 'members']),
            'icon' => 'user-group',
            'title' => trans('common.members'),
            'desc' => trans('common.staffDashboardBlocks.members')
        ];

        return view('staff.index', compact('dashboardBlocks'));
    }

    /**
     * Display the QR scanner.
     *
     * @param string $locale
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showQrScanner(string $locale, Request $request): \Illuminate\View\View
    {
        return view('staff.qr.scanner');
    }
}
