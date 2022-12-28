<?php

namespace App\Http\Livewire\Backend\Navbar;

use Livewire\Component;

class Notification extends Component
{
    public $unReadCount;
    public $unReadNotifications;


    public function getListeners(): array
    {
        $user_id = auth()->id();
        return [
            "echo-notification:users.{$user_id},notification" => 'mount',
        ];
    }

    public function mount()
    {
        $this->unReadCount = auth()->user()->unreadNotifications->count();
        $this->unReadNotifications = auth()->user()->unreadNotifications;
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->unreadNotifications->where('id', $id)->first();
        $notification->markAsRead();
        return redirect()->to($notification->data['order_url']);
    }
    public function render()
    {
        return view('livewire.backend.navbar.notification');
    }
}
