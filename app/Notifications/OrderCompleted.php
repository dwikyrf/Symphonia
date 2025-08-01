<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderCompleted extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Format the email notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Pesanan #' . $this->order->order_number . ' Telah Selesai ✅')
            ->greeting('Halo, ' . $notifiable->name . ' 👋')
            ->line('Kami senang memberitahu bahwa pesanan Anda telah *SELESAI*. 🎉')
            ->line('')
            ->line('**Order Number:** ' . $this->order->order_number)
            ->line('**Tanggal Selesai:** ' . now()->format('d F Y, H:i'))
            ->line('')
            ->action('Lihat Pesanan Anda', route('order.track', $this->order->id))
            ->line('')
            ->line('Terima kasih telah mempercayai ' . config('app.name') . ' untuk kebutuhan Anda.')
            ->salutation('Salam hangat, ' . config('app.name'));
    }
}
