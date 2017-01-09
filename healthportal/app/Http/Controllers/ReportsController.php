<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;

use Excel;

class ReportsController extends Controller
{

    public function ServiceDownload(Request $request){

        function getLetter($num) {
            $num = $num - 1;
            $numeric = $num % 26;
            $letter = chr(65 + $numeric);
            $num2 = intval($num / 26);
            if ($num2 > 0) {
                return getNameFromNumber($num2 - 1) . $letter;
            } else {
                return $letter;
            }
        }

        function addBorderToRow($sheet, $rowCount, $colCount){
            for ($i=1; $i <= $colCount; $i++) { 
                $sheet->setBorder(getLetter($i) . $rowCount, 'thin');
            }
        }

        $info = json_decode($request->get("info"), true);
        $data = json_decode($request->get("data"), true);

        $file = Excel::create('New file', function($excel) use ($info, $data){
            $excel->sheet('New sheet', function($sheet) use ($info, $data){

                $rowCount = 1; 
                $colCount = 1;

                //Headings
                $sheet->mergeCells('A' . $rowCount . ':' . getLetter($info['colCount']) . $rowCount);
                $sheet->cell('A' . $rowCount, function($cell) use ($info){
                    $cell->setValue('SOCIAL HYGIENE CLINIC REPORT');
                    $cell->setFont([
                        'size' => '24',
                        'bold' => true
                    ])->setValignment('center')->setAlignment('center');
                });
                $rowCount++;

                $sheet->mergeCells('A' . $rowCount . ':' . getLetter($info['colCount']) . $rowCount);
                $sheet->cell('A' . $rowCount, function($cell) use ($info){
                    $cell->setValue('SHC Services');
                    $cell->setFont([
                        'size' => '14',
                        'bold' => true
                    ])->setValignment('center')->setAlignment('center');
                });
                $rowCount++;

                $sheet->mergeCells('A' . $rowCount . ':' . getLetter($info['colCount']) . $rowCount);
                $sheet->cell('A' . $rowCount, function($cell) use ($info){
                    $cell->setValue($info["startDate"] . ' — ' . $info["endDate"]);
                    $cell->setFont([
                        'size' => '11'
                    ])->setValignment('center')->setAlignment('center');
                });
                $rowCount++;

                $sheet->mergeCells('A' . $rowCount . ':' . getLetter($info['colCount']) . $rowCount);
                $sheet->cell('A' . $rowCount, function($cell) use ($info){
                    $cell->setValue($info["municipality"] . ', ' . $info["province"] . ', ' . $info["region"]);
                    $cell->setFont([
                        'size' => '11'
                    ])->setValignment('center')->setAlignment('center');
                });

                $rowCount = $rowCount + 2;

                $sheet->mergeCells('A' . $rowCount . ':' . getLetter($info['colCount']) . $rowCount);
                $sheet->cell('A' . $rowCount, function($cell) use ($info){
                    $cell->setValue('3.1 NUMBER OF SHC SERVICE ENCOUNTERS');
                    $cell->setFont([
                        'size' => '12',
                        'bold' => true
                    ])->setValignment('center');
                });

                $rowCount++;
                $startCol = $info['encActiviesColCount']/2 + 2;
                
                $sheet->mergeCells('A' . $rowCount . ':' . getLetter($startCol) . $rowCount);
                $sheet->cell('A' . $rowCount, function($cell) use ($info){
                    $cell->setValue(strtoupper('HIV Prevention Activities'));
                    $cell->setFont([
                        'size' => '11',
                        'bold' => true
                    ])->setValignment('center')->setAlignment('center');
                });

                $newStartCol = $startCol;
                $endCol = $newStartCol + $info['encActiviesNoColCount']/2 + 1;
                addBorderToRow($sheet, $rowCount, $endCol);
                $sheet->mergeCells(getLetter($newStartCol+1) . $rowCount . ':' . getLetter($endCol) . $rowCount);
                $sheet->cell(getLetter($newStartCol+1) . $rowCount, function($cell) use ($info){
                    $cell->setValue(strtoupper('Number of Activities this Period'));
                    $cell->setFont([
                        'size' => '11',
                        'bold' => true
                    ])->setValignment('center')->setAlignment('center');
                });

                $sheet->getStyle('A'.$rowCount . ':' . getLetter($info["colCount"]) . $rowCount)->getAlignment()->setWrapText(true);

                $rowCount++;

                foreach ($data["encounters"] as $key => $encounter) {
                    $index = 0;
                    $row = [];

                    $row[0] = "3.1." . ($key+1);
                    $row[1] = $encounter["label"];

                    for ($i=2; $i < $startCol; $i++) { 
                        $row[$i] = " ";
                    }

                    $i++;

                    $row[$i] = $encounter["count"];

                    $sheet->appendRow($row);
                    $sheet->mergeCells('B' . $rowCount . ':' . getLetter($startCol) . $rowCount);
                    $sheet->mergeCells(getLetter($newStartCol+1) . $rowCount . ':' . getLetter($endCol) . $rowCount);
                    $sheet->cell('A'. ($rowCount), function($cell){
                        $cell->setValignment('center')->setAlignment('center');
                    });
                    $sheet->cells('A'. ($rowCount) . ':' . 'B' . ($rowCount), function($cell){
                        $cell->setFont([
                            'size' => '11',
                            'bold' => true
                        ]);
                    });
                    $sheet->cells(getLetter($startCol+1) . $rowCount . ':' . getLetter($info["colCount"]) . $rowCount, function($cells) use ($info){
                        $cells->setValignment('center')->setAlignment('center');
                    });

                    addBorderToRow($sheet, $rowCount, $endCol);
                    $rowCount++;
                }

                $rowCount++;
                $startCol = $info['colCount']/2 + 1;

                $sheet->mergeCells('A' . $rowCount . ':' . getLetter($info['colCount']) . $rowCount);
                $sheet->cell('A' . $rowCount, function($cell) use ($info){
                    $cell->setValue('3.2 NUMBER OF SHC SERVICE ENCOUNTERS BY KEY AFFECTED POPULATION');
                    $cell->setFont([
                        'size' => '12',
                        'bold' => true
                    ])->setValignment('center');
                });

                $rowCount++;
                $colCount = 5;

                $sheet->mergeCells("A".($rowCount).":" . getLetter($colCount).($rowCount+1));

                $sheet->cell('A' . $rowCount, function($cell) use ($info){
                    $cell->setValue(strtoupper('HIV Prevention Activities'));
                    $cell->setFont([
                        'size' => '11',
                        'bold' => true
                    ])->setValignment('center')->setAlignment('center');
                });
                addBorderToRow($sheet, $rowCount, $info["colCount"]);

                $sheet->getStyle('A'.$rowCount)->getAlignment()->setWrapText(true);

                foreach($data["clients"] as $client) {
                    $colCount++;

                    $sheet->cell(getLetter($colCount) . $rowCount, function($cell) use ($info, $client){
                        $cell->setValue(strtoupper($client["label"]));
                        $cell->setFont([
                            'size' => '11',
                            'bold' => true
                        ])->setValignment('center')->setAlignment('center');
                    });

                    if(count($client["sex"]) > 1){
                        $sheet->mergeCells(getLetter($colCount).($rowCount).":" . getLetter($colCount+1).($rowCount));

                        $sheet->cell(getLetter($colCount) . ($rowCount+1), function($cell) use ($info){
                            $cell->setValue("MALE");
                            $cell->setFont([
                                'size' => '11',
                                'bold' => true
                            ])->setValignment('center')->setAlignment('center');
                        });

                        $sheet->cell(getLetter($colCount+1) . ($rowCount+1), function($cell) use ($info){
                            $cell->setValue("FEMALE");
                            $cell->setFont([
                                'size' => '11',
                                'bold' => true
                            ])->setValignment('center')->setAlignment('center');
                        });

                        $sheet->cell(getLetter($colCount) . $rowCount, function($cell){
                            $cell->setFont([
                                'size' => '11',
                                'bold' => true
                            ])->setValignment('center')->setAlignment('center');
                        });

                        $sheet->getStyle(getLetter($colCount).$rowCount)->getAlignment()->setWrapText(true);
                        $colCount++;
                    }else{                           
                        $sheet->mergeCells(getLetter($colCount).($rowCount).":" . getLetter($colCount).($rowCount+1));

                        $sheet->cell(getLetter($colCount) . $rowCount, function($cell){
                            $cell->setFont([
                                'size' => '11',
                                'bold' => true
                            ])->setValignment('center')->setAlignment('center');
                        });

                        $sheet->getStyle(getLetter($colCount).$rowCount)->getAlignment()->setWrapText(true);
                    }
                }

                $colCount++;

                $sheet->cell(getLetter($colCount) . ($rowCount+1), function($cell) use ($info){
                    $cell->setValue("MALE");
                    $cell->setFont([
                        'size' => '11',
                        'bold' => true
                    ])->setValignment('center')->setAlignment('center');
                });

                $sheet->cell(getLetter($colCount+1) . ($rowCount+1), function($cell) use ($info){
                    $cell->setValue("FEMALE");
                    $cell->setFont([
                        'size' => '11',
                        'bold' => true
                    ])->setValignment('center')->setAlignment('center');
                });

                $sheet->mergeCells(getLetter($colCount).($rowCount).":" . getLetter($colCount+1).($rowCount));
                $sheet->cell(getLetter($colCount) . ($rowCount), function($cell){
                    $cell->setValue("TOTAL");
                    $cell->setFont([
                        'size' => '11',
                        'bold' => true
                    ])->setValignment('center')->setAlignment('center');
                });

                $sheet->getRowDimension($rowCount)->setRowHeight(90);

                addBorderToRow($sheet, $rowCount, $info["colCount"]);
                addBorderToRow($sheet, $rowCount+1, $info["colCount"]);

                $rowCount = $rowCount + 2;
                $startCol = 5;

                foreach($data["encounters"] as $key => $encounter){
                    $index = 0;
                    $row = [];
                    $x = $startCol;

                    $row[0] = "3.2." . ($key+1);
                    $row[1] = $encounter["label"];

                    for ($i=2; $i < $startCol; $i++) { 
                        $row[$i] = " ";
                    }

                    foreach($data["encountersByPop"][$encounter["label"]] as $clientCount){
                        foreach($clientCount as $cell){
                            $row[$x] = $cell["count"];
                            $x++;
                        }
                    }

                    $sheet->appendRow($row);
                    $sheet->mergeCells('B' . $rowCount . ':' . getLetter($startCol) . $rowCount);
                    $sheet->cell('A'. ($rowCount), function($cell){
                        $cell->setValignment('center')->setAlignment('center');
                    });
                    $sheet->cells('A'. ($rowCount) . ':' . 'B' . ($rowCount), function($cells){
                        $cells->setFont([
                            'size' => '11',
                            'bold' => true
                        ]);
                    });
                    $sheet->cells(getLetter($startCol+1) . $rowCount . ':' . getLetter($info["colCount"]) . $rowCount, function($cells) use ($info){
                        $cells->setValignment('center')->setAlignment('center');
                    });

                    addBorderToRow($sheet, $rowCount, $info["colCount"]);
                    $rowCount++;
                }

                for ($i=1; $i <= $info["colCount"]; $i++) { 
                    $sheet->setWidth(getLetter($i), 14);
                }

                $sheet->appendRow(array(
                    '* Transgender men are counted in Total Female'
                ));

                $sheet->appendRow(array(
                    '** Transgender women are counted in Total Male'
                ));

                $sheet->cells('A'.$rowCount.':A'.($rowCount+1), function($cells) {
                    $cells->setFont([
                        'italic' => true,
                    ]);
                });

                $sheet->setWidth('B', 19);
                $sheet->setOrientation('landscape');
            });
        });

        return $file->export('xls');
    }

    public function InventoryDownload(Request $request){
        $info = json_decode($request->get("info"), true);
        $data = json_decode($request->get("data"), true);     

        $context = [
            "info" => $info,
            "data" => $data
        ];

        $file = Excel::create('New file', function($excel) use ($context){
            $excel->sheet('New sheet', function($sheet) use ($context){
                $sheet->loadView('inventory.xlsReportView', $context);
                $sheet->setOrientation('landscape');
            });
        });

        return $file->export('xls');
    }

    public function clientDownload(Request $request){
        function getLetter($num) {
            $num = $num - 1;
            $numeric = $num % 26;
            $letter = chr(65 + $numeric);
            $num2 = intval($num / 26);
            if ($num2 > 0) {
                return getNameFromNumber($num2 - 1) . $letter;
            } else {
                return $letter;
            }
        }

        $info = json_decode($request->get("info"), true);
        $data = json_decode($request->get("data"), true);     

        $context = [
            "info" => $info,
            "data" => $data
        ];

        $file = Excel::create('SHCReps STI Report', function($excel) use ($context){
            $excel->sheet('Client and STI (Default)', function($sheet) use ($context){
                $sheet->loadView('client.xlsReportView', $context);
                $sheet->setOrientation('landscape');

                $sheet->mergeCells('A9:G10');
                $sheet->setBorder('A9:Z10', 'thin');
                $sheet->mergeCells('C14:E19');
                $sheet->mergeCells('C20:E24');
                $sheet->setBorder('A11:G24', 'thin');
                // $sheet->cells('A9:Z10', function($cells) {
                //     $cells->setAlignment('center');
                //     $cells->setValignment('center');
                // });

                $sheet->mergeCells('A27:F28');
                $sheet->setBorder('A27:Y28', 'thin');
                $sheet->setBorder('A29:F32', 'thin');
                // $sheet->cells('A13:Y14', function($cells2) {
                //     $cells2->setAlignment('center');
                //     $cells2->setValignment('center');
                // });

                $sheet->mergeCells('A35:B36');
                $sheet->setBorder('A35:X36', 'thin');
                $sheet->setBorder('A37:B52', 'thin');
                // $sheet->cells('A17:X18', function($cells2) {
                //     $cells2->setAlignment('center');
                //     $cells2->setValignment('center');
                // });
                $sheet->mergeCells('A54:B55');
                $sheet->setBorder('A54:J55', 'thin');
                $sheet->setBorder('A56:B63', 'thin');
                // $sheet->cells('A20:J21', function($cells2) {
                //     $cells2->setAlignment('center');
                //     $cells2->setValignment('center');
                // });
                for ($i=1; $i <= 26; $i++) { 
                    if ($i == 2 || $i == 4) {
                        $sheet->setWidth(getLetter($i), 25);
                    }
                    else {
                        $sheet->setWidth(getLetter($i), 11);
                    }
                }
                $sheet->getStyle('F20:G24')->getAlignment()->setWrapText(true);
                $sheet->getStyle('C36:X36')->getAlignment()->setWrapText(true);
                $sheet->getStyle('C55:J55')->getAlignment()->setWrapText(true);
            });
        });

        return $file->export('xls');
    }

}
