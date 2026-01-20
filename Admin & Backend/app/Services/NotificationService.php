<?php

namespace App\Services;

class NotificationService
{
    public function index($req)
    {
        $page = $req->query('page', 1);
        $limit = $req->query('limit', 5);
        $filter = $req->query('read');
        $search = $req->query('search');

        $query = $req->user()
            ->notifications()
            ->when($filter, function ($query, $filter) {
                // if ($filter === 'read') {
                //     $query->whereNotNull('read_at');
                // }
                if ($filter === 'unread') {
                    $query->whereNull('read_at');

                }

            })
            ->when($search, function ($query, $search) {
                $query->where('data->message', 'like', "%{$search}%");
            });
        $notification = $query->skip(($page - 1) * $limit)
            ->take($limit)
            ->get();
        $total = $query->count();

        return [$notification, $total];
    }
}
