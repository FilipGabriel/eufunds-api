<?php

namespace Modules\Order\Exports;

use Modules\Order\Entities\Order;
use Modules\Core\Traits\Documents;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\TemplateProcessor;

class Offer
{
    use Documents;

    /**
     * @inheritDoc
     */
    public function getData(Order $order): array
    {
        return [
            'company_name' => $order->company_name ?? $this->getMissingTag('Beneficiar'),
            'offer_type' => trans("program::programs.types.{$order->type}"),
            'funding_name' => $order->funding->name ?? $this->getMissingTag('Program de finanțare'),
            'account_manager' => $order->customer->manager_name,
            'account_manager_email' => $order->customer->manager_email,
            'created' => $order->created_at->format('d M Y')
        ];
    }

    public function getExtraRules(Order $order, TemplateProcessor $template)
    {
        $template->setComplexBlock('products_table', $this->getProductsTable($order));

        return $template;
    }

    private function getProductsTable(Order $order)
    {
        $table = new Table($this->defaultTableStyle);
        request()->merge(['program' => $order->program ]);

        $table->addRow();
        $table->addCell(500, array_merge(['valign' => 'center'], $this->cellBgColor))->addText('Nr. crt', $this->titleStyle, $this->noSpace);
        $table->addCell(4000, array_merge(['valign' => 'center'], $this->cellBgColor))->addText('Codul produsului', $this->titleStyle, $this->noSpace);
        $table->addCell(8000, array_merge(['valign' => 'center'], $this->cellBgColor))->addText('Denumirea produsului sau a serviciilor', $this->titleStyle, $this->noSpace);
        $table->addCell(500, array_merge(['valign' => 'center'], $this->cellBgColor))->addText('UM', $this->titleStyle, $this->noSpace);
        // $table->addCell(1000, array_merge(['valign' => 'center'], $this->cellBgColor))->addText('DNSH*', $this->titleStyle, $this->noSpace);
        $table->addCell(1000, array_merge(['valign' => 'center'], $this->cellBgColor))->addText('Stoc', $this->titleStyle, $this->noSpace);
        $table->addCell(1000, array_merge(['valign' => 'center'], $this->cellBgColor))->addText('Cantitate', $this->titleStyle, $this->noSpace);
        $table->addCell(2000, array_merge(['valign' => 'center'], $this->cellBgColor))->addText('Preț unitar (Lei) Fără TVA', $this->titleStyle, $this->noSpace);
        $table->addCell(2000, array_merge(['valign' => 'center'], $this->cellBgColor))->addText('Valoare totală (Lei) Fără TVA', $this->titleStyle, $this->noSpace);
        $table->addCell(500, array_merge(['valign' => 'center'], $this->cellBgColor))->addText('Garanție (luni)', $this->titleStyle, $this->noSpace);

        if($order->products->isEmpty()) {
            $table->addRow(500);
            $table->addCell()->addText('-', $this->defaultFont, $this->noSpace);
            $table->addCell()->addText('-', $this->defaultFont, $this->noSpace);
            $table->addCell()->addText('-', $this->defaultFont, $this->noSpace);
            $table->addCell()->addText('-', $this->defaultFont, $this->noSpace);
            $table->addCell()->addText('-', $this->defaultFont, $this->noSpace);
            $table->addCell()->addText('-', $this->defaultFont, $this->noSpace);
            $table->addCell()->addText('-', $this->defaultFont, $this->noSpace);
            $table->addCell()->addText('-', $this->defaultFont, $this->noSpace);
            $table->addCell()->addText('-', $this->defaultFont, $this->noSpace);
        }

        foreach($order->products as $key => $product) {
            $table->addRow(500);
            $table->addCell(null, ['valign' => 'center'])->addText($key+1, $this->defaultFont, $this->noSpace);
            
            $table->addCell(null, ['valign' => 'center'])->addText($product->product->sku ?: '-', $this->defaultFont, $this->noSpace);
            $table->addCell(null, ['valign' => 'center'])->addText($product->product->name, $this->defaultFont, $this->noSpace);
            $table->addCell(null, ['valign' => 'center'])->addText('Buc', $this->defaultFont, $this->noSpace);
            // $table->addCell(null, ['valign' => 'center'])->addText($product->hasAnyOption() ? $this->checkbox() : $this->prohibited(), $this->defaultFont, $this->noSpace);
            $table->addCell(null, ['valign' => 'center'])->addText($product->product->qty, $this->defaultFont, $this->noSpace);
            $table->addCell(null, ['valign' => 'center'])->addText($product->qty, $this->defaultFont, $this->noSpace);
            $table->addCell(null, ['valign' => 'center'])->addText($product->unit_price->format('RON'), $this->defaultFont, $this->noSpace);
            $table->addCell(null, ['valign' => 'center'])->addText($product->line_total->format('RON'), $this->defaultFont, $this->noSpace);
            $table->addCell(null, ['valign' => 'center'])->addText($product->product->warranty ?: '-', $this->defaultFont, $this->noSpace);
        }

        if($order->products->isNotEmpty()) {
            $table->addRow(500);
            $cell = $table->addCell(null, ['valign' => 'center']);
            $cell->getStyle()->setGridSpan(7);
            $cell->addText('TOTAL', $this->titleStyle, $this->noSpace);

            $cell = $table->addCell(null, ['valign' => 'center']);
            $cell->getStyle()->setGridSpan(2);
            $cell->addText($order->total->format('RON'), ['name' => 'Roboto Condensed', 'size' => 16, 'color' => '323E4F', 'bold' => true], $this->noSpace);
        }

        return $table;
    }
}
