<div>
    <a href="#" class="nav-link dropdown-toggle withoutAfter" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="badge badge-danger badge-counter">{{ $unReadCount }}</span>
        <i class="fas fa-bell mr-2 text-gray"></i>
    </a>
    <div class="dropdown-menu mt-3 notification-dropdown" aria-labelledby="notificationDropdown">
        @forelse($unReadNotifications as $notification)
            @if($notification->type == 'App\Notifications\Frontend\Customer\OrderThanksNotification')
            <a class="dropdown-item d-flex align-items-center" wire:click="markAsRead('{{ $notification->id }}')">
                <div class="mr-3">
                    <div class="icon-circle bg-primary">
                        <i class="fas fa-file-alt text-white"></i>
                    </div>
                </div>
                <div>
                    <div class="small text-gray-500">{{ $notification->data['created_date'] }}</div>
                    <span class="font-weight-bold">Check your email to details for your order {{ $notification->data['order_ref_id'] }} </span>
                </div>
            </a>
            @endif

            @if($notification->type == 'App\Notifications\Frontend\Customer\OrderCreatedNotification')
            <a class="dropdown-item d-flex align-items-center" wire:click="markAsRead('{{ $notification->id }}')">
                <div class="mr-3">
                    <div class="icon-circle bg-primary">
                        <i class="fas fa-file-alt text-white"></i>
                    </div>
                </div>
                <div>
                    <div class="small text-gray-500">{{ $notification->data['created_date'] }}</div>
                    <span class="font-weight-bold">Your order {{ $notification->data['order_ref_id'] }} status is {{ $notification->data['last_transaction'] }}</span>
                </div>
            </a>
            @endif

        @empty
            <div class="dropdown-item text-center">No notifications found</div>
        @endforelse
    </div>
</div>
