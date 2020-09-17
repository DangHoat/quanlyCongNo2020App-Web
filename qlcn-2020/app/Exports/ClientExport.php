<?php

namespace App\Exports;

use App\Client;
use App\Bill;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use \Maatwebsite\Excel\Sheet;

class ClientExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithCustomStartCell
{
	function __construct() {
	    $this->count = 3;
        $this->sum = 0;
	}
    /**
    * @return \Illuminate\Support\Collection
    */
    //FromCollection
	public function collection(){
		$index = 1;
        $clients = Client::where('status','!=','resolved')
            ->where('total','!=',0)
            ->get(['id','code','name','address','telephone','total','note'])
            ->toArray()
            ;
        foreach ($clients as $client) {
        	$client['id'] = $index;
            $this->sum += $client['total'];
        	$data[] = $client;
        	$index++;
        	$this->count++;
        }
        return (collect($data));
    }

    //WithHeadings
    public function headings(): array{
        $mday = getdate()['mday'];
        if($mday < 10)  $mday = '0'.$mday;
        $mon = getdate()['mon'];
        if($mon < 10)  $mon = '0'.$mon;
        $year = getdate()['year'];
        return [
           ['BẢNG TỔNG HỢP KHÁCH HÀNG ĐANG NỢ'],
           ['ngày '.$mday.' tháng '.$mon.' năm '.$year],
           [
            'STT',
            'Mã khách',
            'Tên khách',
            'Địa chỉ',
            'SĐT',
            'Tổng nợ',
            'Ghi chú'
        	]
        ];
    }

    //WithEvents
    public function registerEvents(): array
    {
    	Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
		    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
		});
        return [
            AfterSheet::class    => function(AfterSheet $event) {
            	$event->sheet->getParent()->getDefaultStyle()
            				->getFont()
            				->setSize(13)
            				->setName('Times New Roman');
            	$event->sheet->getParent()->getActiveSheet()
            				->mergeCells('A1:G1')
            				->mergeCells('A2:G2')
            				->mergeCells('A'.($this->count+1).':E'.($this->count+1))
            				->mergeCells('A'.($this->count+2).':G'.($this->count+2))
            				->mergeCells('E'.($this->count+3).':G'.($this->count+3))
            				->setCellValue('A'.($this->count+1),'TỔNG CỘNG')
            				// ->setCellValue('F'.($this->count+1),'=SUM(F4:F'.($this->count).')')
                            ->setCellValue('F'.($this->count+1),$this->sum)
            				->setCellValue('A'.($this->count+2),'(Bằng chữ:..........................................................................................................................)')
            				->setCellValue('E'.($this->count+3),'GĐ KINH DOANH');
                $event->sheet->getDelegate()->getStyle('A'.($this->count+2))->getFont()
                		->setSize(11)
                		->setItalic(true);
                $event->sheet->getDelegate()->getStyle('E'.($this->count+3))->getFont()
                		->setBold(true);		
                $event->sheet->styleCells(
                    'A1:G'.($this->count+1),
                    [
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ],
                        ],
                        'alignment' => [
					        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
					        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
					    ],
                    ]
                );
                $event->sheet->styleCells(
                    'A4:G'.($this->count),
                    [
                        'alignment' => [
					        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
					    ],
                    ]
                );
                $event->sheet->styleCells(
                    'F4:F'.($this->count+1),
                    [
                        'alignment' => [
					        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
					    ],
					    'numberFormat' => [
					        'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3
					    ]
                    ]
                );
                $event->sheet->styleCells(
                    'A'.($this->count+2).':G'.($this->count+3),
                    [
                        'alignment' => [
					        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
					        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
					    ],
                    ]
                );
                $event->sheet->styleCells(
                    'A3:G3',
                    [
					    'font' => [
					        'bold' => true,
					    ],
					    'fill' => [
					        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
					        'rotation' => 90,
					        'startColor' => [
					            'argb' => 'FFA0A0A0',
					        ],
					        'endColor' => [
					            'argb' => 'FFFFFFFF',
					        ],
					    ],
                    ]
                );
                $event->sheet->styleCells(
                    'A1:G1',
                    [
					    'font' => [
					        'bold' => true,
					        'size' => 18,
					    ],
                    ]
                );
                $event->sheet->styleCells(
                    'A2:G2',
                    [
					    'font' => [
					        'bold' => true,
					        'italic' => true,
					        'size' => 14,
					    ],
                    ]
                );
                $event->sheet->styleCells(
                    'A'.($this->count+1).':G'.($this->count+1),
                    [
					    'font' => [
					        'bold' => true,
					        'size' => 16,
					    ],
                    ]
                );
            },
        ];
    }

    //WithCustomStartCell
    public function startCell(): string
    {
        return 'A1';
    }
}
