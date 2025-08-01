<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\OrderTracking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderTrackingUpdated extends Notification
{
    use Queueable;

    protected $order;
    protected $tracking;

    public function __construct(Order $order, OrderTracking $tracking)
    {
        $this->order = $order;
        $this->tracking = $tracking;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Update Status Pesanan #' . $this->order->order_number)
            ->greeting('Halo, ' . $notifiable->name . ' 👋')
            ->line('Kami ingin memberitahukan bahwa status pesanan Anda telah diperbarui.')
            ->line('')
            ->line('**Status Terbaru:**')
            ->line('➡️ ' . strtoupper($this->tracking->status))
            ->line('')
            ->line('**Tanggal Update:** ' . now()->format('d F Y, H:i'))
            ->action('Lihat Detail Pesanan', route('order.track', $this->order->id))
            ->line('')
            ->line('Terima kasih telah berbelanja di ' . config('app.name') . '.')
            ->salutation('Salam hangat, ' . config('app.name'));
    }
    
}
