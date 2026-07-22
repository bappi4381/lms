<?php

namespace App\Notifications;

use App\Models\Lesson;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewLessonAddedNotification extends Notification
{
    use Queueable;

    public function __construct(protected Lesson $lesson)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $course = $this->lesson->module->course;

        return (new MailMessage)
            ->subject("নতুন লেসন যুক্ত হয়েছে: {$course->title}")
            ->greeting("প্রিয় {$notifiable->name},")
            ->line("আপনার এনরোল করা কোর্স \"{$course->title}\"-এ একটি নতুন লেসন যুক্ত হয়েছে:")
            ->line("📚 {$this->lesson->title}")
            ->action('এখনই দেখুন', route('courses.lesson', ['slug' => $course->slug, 'lesson_id' => $this->lesson->id]))
            ->line('আপনার সাথে শেখার এই যাত্রায় ধন্যবাদ।');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'lesson_id' => $this->lesson->id,
            'lesson_title' => $this->lesson->title,
            'course_id' => $this->lesson->module->course_id,
            'course_title' => $this->lesson->module->course->title,
            'message' => "নতুন লেসন যুক্ত হয়েছে: {$this->lesson->title}",
        ];
    }
}
