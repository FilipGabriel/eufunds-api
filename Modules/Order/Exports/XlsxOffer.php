<?php

namespace Modules\Order\Exports;

use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class XlsxOffer implements FromCollection, WithEvents, ShouldAutoSize, WithStrictNullComparison, WithColumnWidths, WithTitle
{
    use Exportable, RegistersEventListeners;

    private $order;
    private $countProducts = 0;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function title(): string
    {
        return 'Oferta';
    }

    /**
     * @return array
     */
    public function collection()
    {
        $data[] = [
            [
                'A' => "",'B' => "Beneficiar",
                'C' => $this->order->company_name ?? '-',
                'D' => "", 'E' => "", 'F' => "", 'G' => "",
                'H' => "", 'I' => "", 'J' => "", 'K' => "",
            ],
            [
                'A' => "",'B' => "Partener",
                'C' => $this->order->partner ?? '-',
                'D' => "", 'E' => "", 'F' => "", 'G' => "",
                'H' => "", 'I' => "", 'J' => "", 'K' => "",
            ],
            [
                'A' => "", 'B' => "Program de finanțare",
                'C' => $this->order->funding->name ?? '-',
                'D' => "", 'E' => "", 'F' => "", 'G' => "",
                'H' => "", 'I' => "", 'J' => "", 'K' => "",
            ],
            [
                'A' => "", 'B' => "", 'C' => "", 'D' => "", 'E' => "", 'F' => "",
                'G' => "", 'H' => "", 'I' => "", 'J' => "", 'K' => "",
            ],
            [
                'A' => "Nr. crt",
                'B' => "Codul produsului",
                'C' => "Denumirea produsului sau a serviciilor",
                'D' => "UM",
                'E' => "Stoc",
                'F' => "Cantitate",
                'G' => "Preț unitar ({$this->order->currency}) Fără TVA",
                'H' => "Valoare totală ({$this->order->currency}) Fără TVA",
                'I' => "Garanție (luni)",
                'J' => "Termen de livrare standard",
                'K' => "Observații",
            ]
        ];

        $this->countProducts = count($this->order->products);

        foreach($this->order->products as $key => $product) {
            $data[] = [
                'A' => $key+1,
                'B' => $product->product->sku ?: '-',
                'C' => strip_tags($product->product->short_description),
                'D' => 'Buc',
                'E' => $product->product->getRealStock(),
                'F' => $product->qty,
                'G' => $product->unit_price->convert($this->order->currency, $this->order->currency_rate)->format($this->order->currency),
                'H' => $product->line_total->convert($this->order->currency, $this->order->currency_rate)->format($this->order->currency),
                'I' => $product->product->warranty ?: '-',
                'J' => $product->product->shipping ?: '-',
                'K' => '-',
            ];
        }

        return collect($data);
    }

    public static function afterSheet(AfterSheet $event)
    {
        $border = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ]
            ]
        ];

        $rowStart = 5;
        $rowEnd = $rowStart + $event->getConcernable()->countProducts;

        $event->sheet->getDelegate()->getStyle("B1")->getFont()->setBold(true);
        $event->sheet->getDelegate()->getStyle("C1")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $event->sheet->getStyle("C1")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $event->sheet->getDelegate()->getStyle("B2")->getFont()->setBold(true);
        $event->sheet->getDelegate()->getStyle("C2")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $event->sheet->getStyle("C2")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $event->sheet->getDelegate()->getStyle("B3")->getFont()->setBold(true);
        $event->sheet->getDelegate()->getStyle("C3")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $event->sheet->getStyle("C3")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $event->sheet->getDelegate()->getStyle("A{$rowStart}:K{$rowEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $event->sheet->getStyle("A{$rowStart}:K{$rowEnd}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];
        for($value = $rowStart; $value <= $rowEnd; $value++) {
            $event->sheet->getDelegate()->getRowDimension($value)->setRowHeight($value == $rowStart ? 40 : 200);

            foreach($columns as $column) {
                if($value == $rowStart) {
                    $event->sheet->getDelegate()->getStyle("{$column}{$value}")->getFont()->setBold(true);
                }

                $event->sheet->getDelegate()->getStyle("{$column}{$value}")->applyFromArray(array_merge($border, $value == $rowStart ? [
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['argb' => 'ffd6dce4']
                    ]
                ] : []));
                $event->sheet->getDelegate()->getStyle("{$column}{$value}")->getAlignment()->setWrapText(true);
            }
        }
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 20,
            'C' => 80,
            'D' => 10,
            'E' => 20,
            'F' => 10,
            'G' => 20,
            'H' => 20,
            'I' => 10,
            'J' => 30,
            'K' => 40,
        ];
    }
}
