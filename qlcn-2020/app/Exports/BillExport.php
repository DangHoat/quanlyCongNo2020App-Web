<?php

namespace App\Exports;

use App\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use \Maatwebsite\Excel\Sheet;

class BillExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithCustomStartCell
{
	protected $id;

	function __construct($id) {
	    $this->id = $id;
	    $this->count = 3;
        $this->sum = 0;
	}
    /**
    * @return \Illuminate\Support\Collection
    */
    //FromCollection
	public function collection(){
		$index = 1;
        $bills = Client::find($this->id)->bill()
        	->orderBy('date', 'asc')
            ->get(['id','date','construction_address','categories','types','unit','quantity','unit_price','total_amount','note'])->toArray();
        foreach ($bills as $bill) {
        	$bill['id'] = $index;
            $this->sum += $bill['total_amount'];
        	$data[] = $bill;
        	$index++;
        	$this->count++;
        }
        return (collect($data));
    }

    //WithHeadings
    public function headings(): array{
    	$client = Client::find($this->id);
        $mday = getdate()['mday'];
        if($mday < 10)  $mday = '0'.$mday;
        $mon = getdate()['mon'];
        if($mon < 10)  $mon = '0'.$mon;
        $year = getdate()['year'];
        return [
           ['BẢNG CHI TIẾT CÔNG NỢ'],
        //    ['KHÁCH: '.$client->name.' ('.$client->code.')'],
           ['ngày '.$mday.' tháng '.$mon.' năm '.$year],
           [
            'STT',
            'Ngày',
            'Địa chỉ công trình',
            'Hạng mục',
            'Chủng loại hàng',
            'ĐVT',
            'Số lượng',
            'Đơn giá',
            'Thành tiền',
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
            				->mergeCells('A1:J1')
            				->mergeCells('A2:J2')
                            // ->mergeCells('A3:J3')
            				->mergeCells('A'.($this->count+1).':E'.($this->count+1))
            				->mergeCells('A'.($this->count+2).':H'.($this->count+2))
            				->mergeCells('G'.($this->count+3).':I'.($this->count+3))
            				->setCellValue('A'.($this->count+1),'TỔNG CỘNG')
            				// ->setCellValue('I'.($this->count+1),'=SUM(I5:I'.($this->count).')')
                            ->setCellValue('I'.($this->count+1),$this->sum)
            				->setCellValue('A'.($this->count+2),'(Bằng chữ:..........................................................................................................................)')
            				->setCellValue('G'.($this->count+3),'GĐ KINH DOANH');
                $event->sheet->getDelegate()->getStyle('A'.($this->count+2))->getFont()
                		->setSize(11)
                		->setItalic(true);
                $event->sheet->getDelegate()->getStyle('G'.($this->count+3))->getFont()
                		->setBold(true);		
                $event->sheet->styleCells(
                    'A1:J'.($this->count+1),
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
                    // 'A5:J'.($this->count),
                    'A4:J'.($this->count),
                    [
                        'alignment' => [
					        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
					    ],
                    ]
                );
                $event->sheet->styleCells(
                    // 'G5:I'.($this->count+1),
                    'G4:I'.($this->count+1),
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
                    'A'.($this->count+2).':J'.($this->count+3),
                    [
                        'alignment' => [
					        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
					        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
					    ],
                    ]
                );
                $event->sheet->styleCells(
                    // 'A4:J4',
                    'A3:J3',
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
                    'A1:J1',
                    [
                        'font' => [
                            'bold' => true,
                            'size' => 18,
                        ],
                    ]
                );
                // $event->sheet->styleCells(
                //     'A2:J2',
                //     [
				// 	    'font' => [
				// 	        'bold' => true,
				// 	        'size' => 18,
				// 	        'color' => [
				// 	        	'rgb' => 'FF0000',
				// 	        ],
				// 	    ],
                //     ]
                // );
                $event->sheet->styleCells(
                    // 'A3:J3',
                    'A2:J2',
                    [
					    'font' => [
					        'bold' => true,
					        'italic' => true,
					        'size' => 14,
					    ],
                    ]
                );
                $event->sheet->styleCells(
                    'A'.($this->count+1).':J'.($this->count+1),
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
