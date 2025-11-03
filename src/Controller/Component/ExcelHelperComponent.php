<?php

declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

/**
 * ExcelHelper component
 */
class ExcelHelperComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected array $_defaultConfig = [
        'defaultDimension'  =>  15,
        'defaultCellValue'  =>  ''
    ];

    public function initialize(array $config): void
    {
        parent::initialize($config);
    }

    /**
     * Metodos con para modificaciones masivas
     */

    public function setMassiveExcelColumnDimension(&$activeSheet, $arrayDimensions,$offset=0)
    {
        $cant = $offset + count($arrayDimensions) ;
        for ($i = $offset; $i < $cant; $i++) {
            $dimension = isset($arrayDimensions[$i]) ? $arrayDimensions[$i] : $this->getConfig('defaultDimension');
            $currentColumnNumber = $i ;
            if($dimension == 0){
                $this->setAutoSizeColumnDimension($activeSheet,$currentColumnNumber);
            }else{
                $this->setColumnDimension($activeSheet,$currentColumnNumber,$dimension);
            }
        }
    }

    public function setMassiveExcelCellValue(&$activeSheet, $index, $arrayCellValues,$offset=0){
        
        $cant = $offset + count($arrayCellValues) ;
        $j = 0;
        for ($i = $offset; $i < $cant; $i++) {
            $cellValue = isset($arrayCellValues[$j]) ? $arrayCellValues[$j] : $this->getConfig('defaultCellValue');
            $currentColumnNumber = $i ;
            $this->setCellValue($activeSheet,$currentColumnNumber,$index,$cellValue);
            $j++;
        }
    }

    public function setMassiveFontBolder(&$activeSheet, $totalColumnsNumber,$index,$offset=0){
        for ($i = $offset; $i < $totalColumnsNumber; $i++) {
            $currentColumnNumber = $i ;
            $this->setCellBold($activeSheet,$currentColumnNumber,$index);
        }
    }

    public function setMassiveCellBorder(&$activeSheet, $totalColumnsNumber,$index,$offset=0){
        $totalColumnsNumber = $totalColumnsNumber + $offset;
        for ($i = $offset; $i < $totalColumnsNumber; $i++) {
            $currentColumnNumber = $i ;
            $this->setCellBorder($activeSheet,$currentColumnNumber,$index);
        }
    }


    /**
     * Metodos con para celdas individuales
     */

    public function setColumnDimension(&$activeSheet, $number, $dimension)
    {
        $letterColumn = $this->getExcelLetterFromNumber($number);
        $activeSheet->getColumnDimension($letterColumn)->setWidth($dimension);
    }

    public function setAutoSizeColumnDimension(&$activeSheet, $number){
        $letterColumn = $this->getExcelLetterFromNumber($number);
        $activeSheet->getColumnDimension($letterColumn)->setAutoSize(true);    
    }


    

    public function setCellValue(&$activeSheet, $number, $index, $value)
    {
        $letterColumn = $this->getExcelLetterFromNumber($number);
        $activeSheet->setCellValue("{$letterColumn}{$index}",$value);
    }


    

    public function setCellBold(&$activeSheet, $number, $index){
        $letterColumn = $this->getExcelLetterFromNumber($number);
        $activeSheet->getStyle("{$letterColumn}{$index}")->getFont()->setBold(true);
    }


    public function setStyle(&$activeSheet, $columnsNumber, $index, $cellStyleArray,$offset=0){
        
        $cellOffsetTop = $offset + ($columnsNumber - 1);
        $cellPosition = $this->getCellPosition([$offset,$cellOffsetTop],$index);

        $activeSheet->getStyle($cellPosition)->applyFromArray($cellStyleArray);
    }

    public function setCellBorder(&$activeSheet, $number, $index){
        $letterColumn = $this->getExcelLetterFromNumber($number);
        $activeSheet->getStyle("{$letterColumn}{$index}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    }


    /**
     * Retorna la columna e indice de una tabla de excel
     * considerando que se le pase un numero, que puede ser un entero
     * o un array de 1 o 2 numeros
     */
    private function getCellPosition($number,$index){
        $cellPositionString = "";

        if(gettype($number) == 'integer'){
            $cellPosition  = $this->getExcelLetterFromNumber($number);
            $cellPositionString= "{$cellPosition}{$index}";
        }elseif(
            gettype($number)   ==   'array'
        ){
            if(!(isset($number[1])) && isset($number[0])){
                $cellLetter  = $this->getExcelLetterFromNumber($number[0]);
                $cellPositionString= "{$cellLetter}{$index}";
            }elseif( isset($number[0]) && isset($number[1]) ){
                $cellLetter0= $this->getExcelLetterFromNumber($number[0]);
                $cellLetter1= $this->getExcelLetterFromNumber($number[1]);
                $cellPositionString= "{$cellLetter0}{$index}:{$cellLetter1}{$index}";
            }
        }

        return $cellPositionString;
    }


    /**
     * Obtiene la letra de la columna de excel a partir de un numero
     * el numero esperado es mayor o igual 0, el indice inicial, para A, es 0
     */
    private function getExcelLetterFromNumber($num)
    {
        $numeric = $num % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval($num / 26);
        if ($num2 > 0) {
            return $this->getExcelLetterFromNumber($num2 - 1) . $letter;
        } else {
            return $letter;
        }
    }
}
