<?php

namespace Modules\Checkout\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use PhpOffice\PhpWord\Settings;
use Modules\Media\Entities\File;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Contracts\Queue\ShouldQueue;

class Invoice extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The instance of the order.
     *
     * @var \Modules\Order\Entities\Order
     */
    public $order;

    /**
     * Create a new message instance.
     *
     * @param \Modules\Order\Entities\Order $order
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        app()->setLocale($this->order->locale);

        $this->order->load('products');
        
        $name = trim($this->order->company_name);
        $orderFile = $this->generateOrderTemplate($name);

        return $this->subject(trans('appfront::invoice.subject', ['id' => $this->order->id]))
            ->view("emails.invoice", [
                'logo' => File::findOrNew(setting('appfront_mail_logo'))->path,
            ])
            ->attach($orderFile, [
                'as' => "Oferta - {$name}.doc",
                'mime' => 'application/msword',
            ]);
    }

    private function generateOrderTemplate($name)
    {
        $class = 'Modules\\Order\\Exports\\Offer';
        $document = new $class();
        
        $params = $document->getData($this->order);
        $path = public_path("templates/order.docx");
        Settings::setOutputEscapingEnabled(true);
        $template = new TemplateProcessor($path);
        $template->setValues($params);

        if(method_exists($document, 'getExtraRules')) {
            $template = $document->getExtraRules($this->order, $template);
        }

        $fileName = "Oferta - {$name}";

        return $this->saveFile($fileName, $template);
    }

    /**
     * @param string $file
     * @param string $html
     * @return string
     */
    private function saveFile(string $fileName, TemplateProcessor $template): string
    {
        if (! is_dir(storage_path("offers"))) {
            mkdir(storage_path("offers"));
        }

        $path = storage_path("offers");
        $file = "{$path}/{$fileName}.doc";
        $template->saveAs($file);

        return $file;
    }
}
