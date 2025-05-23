<?php

namespace Src\Services;

use PDOException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Src\Core\Database;
use Src\Models\Voter;
use Src\Repositories\VoterRepository;
use TypeError;

class VoterService
{

    public function __construct(
        protected Database $database,
        protected VoterRepository $voterRepository
    ) {}

    /** @return array<Voter> */
    public function getAllVoters(): array
    {
        return $this->voterRepository->getAllVoters();
    }

    public function import($importedFile)
    {
        $text = shell_exec("pdftotext " . $importedFile->tmp_name . " -");

        $matches = null;

        if (preg_match_all("/Prec : \w+/", $text, $precincts)) {
            $precinctMap = [];

            foreach ($precincts[0] as $precinct) {
                $precinctMap[$precinct] = $precinct;
            }

            $precincts = array_map(
                fn($prec) => str_replace("Prec : ", "", $prec),
                array_keys($precinctMap)
            );
        }

        if (preg_match_all(
            "/\n{1,2}(?:[*A-D]{1,3}\h+)?([\\p{L}.?_'-]+(?:\h+[\\p{L}.?_'-]+)*(?:,\h*(?:[JS]R\.?|J\h*R\.?|II\.?|III\.?|IV\.?|VI{0,3}|IX|X|V))?,\h*[\\p{L}\h.\"'?._-]+(?:,?\h*(?:[JS]R\.?|J\h*R\.?|II\.?|III\.?|IV\.?|VI{0,3}|IX|X|V)\h*)?[\\p{L}\h.\"'?._-]*(?:\h*\"[\\p{L}\s.'\"?_ -]*\")?)(?=\n\n)/u",
            $text,
            $matches
        )) {

            $index = 0;
            $precinctChanged = false;
            $voters = [];

            $legendCombinations = [
                '*',
                'A',
                'B',
                'C',
                'D',
                '*A',
                '*B',
                '*D',
                'AB',
                'AC',
                'AD',
                'BC',
                'BD',
                'CD',
                '*AB',
                '*AD',
                '*BD',
                'ABC',
                'ABD',
                'ACD',
                'BCD',
                'ABCD',
                '*ABD'
            ];

            foreach ($matches[0] as $match) {
                $name = trim($match);

                $parts = explode(" ", $name);

                if (in_array($parts[0], $legendCombinations)) {
                    array_shift($parts);
                    $name = implode(" ", $parts);
                }

                if (str_contains($name, 'AROROY') || str_contains($name, 'MASBATE')) {
                    continue;
                }

                if ($name[0] != 'A') {
                    $precinctChanged = true;
                }

                if ($name[0] == 'A' && $precinctChanged) {
                    $precinctChanged = false;

                    if ($index < sizeof($precincts) - 1) {
                        $index++;
                    }
                }

                $voter = new Voter();
                $voter->name = $name;

                $voter->precinct = $precincts[$index];

                $voters[] = $voter;
            }


            try {
                $this->voterRepository->createVoter($voters);
            } catch (PDOException $e) {
                return json($e->getMessage());
            }
        }
    }

    public function changeStatus(string $voterId, bool $value)
    {
        try {

            $this->voterRepository->changeStatus($voterId, $value);
        } catch (PDOException $e) {
        }
    }

    public function export(): string
    {
        $voters = $this->voterRepository->getAllVoters();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Voter ID');
        $sheet->setCellValue('B1', 'Full Name');
        $sheet->setCellValue('C1', 'Precinct');

        $columns = ['A', 'B', 'C'];

        foreach ($columns as $col) {
            $sheet->getColumnDimension($col)->setWidth(50);
            $sheet->getStyle("{$col}1")->getFont()->setSize(16)->setBold(true);
            $sheet->getStyle("{$col}1")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
        }

        foreach ($voters as $index => $voter) {
            $row = $index + 2;

            $sheet->getRowDimension($row)->setRowHeight(25);

            foreach ($columns as $col) {
                $sheet->getColumnDimension($col)
                    ->setWidth(50);
                $sheet->getStyle("{$col}{$row}")
                    ->getFont()
                    ->setSize(12);
                $sheet->getStyle("{$col}{$row}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
            }

            $sheet->setCellValue("A{$row}", $voter->voterId);
            $sheet->setCellValue("B{$row}", $voter->name);
            $sheet->setCellValue("C{$row}", $voter->precinct);

            if ($voter->isGiven) {
                $range = $sheet->getStyle("A{$row}:C{$row}");

                $range->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB("9E005DFF");
            }
        }

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="voters.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        ob_start();
        $writer->save('php://output');
        $excelOutput = ob_get_contents();
        ob_end_clean();

        return base64_encode($excelOutput);
    }

    public function clearVoters()
    {
        $this->voterRepository->clearVoters();
    }
}
