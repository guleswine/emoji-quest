<?php

namespace App\Services;

use App\Repositories\MapRepository;
use Illuminate\Support\Facades\DB;

class PathSearchService
{
    protected $cells;
    protected $start_cell;
    protected $finish_cell;
    protected $best_path = null;
    protected $max_map_radius = 10;
    protected $cell_radius = 0;

    protected $visited_cells = [];

    public function getCells()
    {
        return $this->cells;
    }

    public function getVisitedCells()
    {
        return $this->visited_cells;
    }

    public function setMaxMapRadius(int $number)
    {
        $this->max_map_radius = $number;

        return $this;
    }

    public function setCellRadius(int $number)
    {
        $this->cell_radius = $number;

        return $this;
    }

    public function getCellById($cell_id)
    {
        $start_cell = DB::table('cells')
            ->where('id', $cell_id)
            ->first();

        return $start_cell;
    }

    public function setStartCellById($cell_id)
    {
        $start_cell = $this->getCellById($cell_id);
        $this->setStartCell($start_cell);

        return $this;
    }

    public function setStartCell($cell)
    {
        $this->start_cell = $cell;

        return $this;
    }

    public function setFinishCellById($cell_id)
    {
        $finish_cell = $this->getCellById($cell_id);
        $this->setFinishCell($finish_cell);

        return $this;
    }

    public function setFinishCell($cell)
    {
        $this->finish_cell = $cell;

        return $this;
    }

    public function loadCells()
    {
        $cells = MapRepository::getFormatedCells($this->start_cell->map_id, $this->start_cell->x, $this->start_cell->y, $this->max_map_radius, $this->max_map_radius);
        $cells = collect($cells);
        $cells = $cells->keyBy(function (object $item, int $key) {
            return $item->x . ':' . $item->y;
        });
        $this->cells = $cells;

        return $this;
    }

    public static function rangeBetwenCells($start_cell, $finish_cell)
    {
        return MapService::rangeBetweenCoords($start_cell->x,$start_cell->y,$finish_cell->x,$finish_cell->y);
    }

    public function checkShortStepsToCell($current_cell, $way)
    {

        $count_current_way = count($way);
        $cell_steps = $this->cells[$current_cell->x . ':' . $current_cell->y]->count_steps ?? 999;
        if ($count_current_way < $cell_steps) {
            $this->cells[$current_cell->x . ':' . $current_cell->y]->count_steps = $count_current_way;

            return true;
        } else {
            return false;
        }
    }

    public function getShortestPath()
    {
        $data = $this->chooseWay($this->start_cell, $this->finish_cell, [], true);
        if ($data) {
            return collect(array_values($data['way']));
        } else {
            return collect();
        }
    }

    public function storeBestPath($way)
    {
        if (!$this->best_path) {
            $this->best_path = $way;
        } elseif (count($this->best_path) > count($way)) {
            $this->best_path = $way;
        }
    }

    public function checkUnlessWay($current_cell, $current_way)
    {
        if (!$this->best_path) {
            return false;
        }
        $min_steps_to_finish = $this->rangeBetwenCells($current_cell, $this->finish_cell) + count($current_way) - 1;
        if ($min_steps_to_finish > (count($this->best_path) - 1)) {
            return true;
        }
    }

    public function chooseWay($start_cell, $finish_cell, $way, $is_good_way = true, $first_cell = true)
    {

        if (!$start_cell) {
            return false;
        }
        /*
        if ($way->firstWhere('id', $start_cell->id)){
            return  false;
        }
        */
        if (isset($way[$start_cell->id])) {
            return false;
        }
        if (!$first_cell) {
            if ($start_cell->surface_type == 'impassable' or in_array($start_cell->object_class, ['Building', 'Hero', 'Unit', 'NPC'])) {
                return false;
            }
            if ($start_cell->surface_type == 'water') {
                return false;
            }
        }

        if ($this->checkUnlessWay($start_cell, $way)) {
            return false;
        }
        if (!$this->checkShortStepsToCell($start_cell, $way)) {
            return false;
        }
        $this->visited_cells[] = $start_cell;
        $way += [$start_cell->id => $start_cell];
        //$way->push($start_cell);

        if ($this->rangeBetwenCells($start_cell, $finish_cell) <= $this->cell_radius) {
            $this->storeBestPath($way);

            return [
                'way' => $way,
                'way_is_good' => $is_good_way,
            ];

        }
        $directions = [
            1 => ['y' => -1, 'x' => 0],
            2 => ['y' => 1, 'x' => 0],
            3 => ['y' => 0, 'x' => -1],
            4 => ['y' => 0, 'x' => 1],
        ];
        $dif_y = 0;
        $dif_x = 0;
        if ($start_cell->y > $finish_cell->y) {
            $dif_y = -1;
            unset($directions[1]);
        } elseif ($start_cell->y < $finish_cell->y) {
            $dif_y = 1;
            unset($directions[2]);
        }
        if ($start_cell->x > $finish_cell->x) {
            $dif_x = -1;
            unset($directions[3]);
        } elseif ($start_cell->x < $finish_cell->x) {
            $dif_x = 1;
            unset($directions[4]);
        }

        $best_way = false;
        if ($dif_y !== 0) {
            $key = $start_cell->x . ':' . $start_cell->y + $dif_y;
            $new_cell = $this->cells[$key] ?? null;
            //$new_cell = $cells->where('x',$start_cell->x)->where('y',$start_cell->y+$dif_y)->first();
            $good_way = $this->chooseWay($new_cell, $finish_cell, $way, true, false);
            if (!$best_way) {
                $best_way = $good_way;
            }
            if ($good_way and $good_way['way_is_good']) {
                // Log::info(print_r(Arr::pluck($way,'id'),1));
                $good_way['way_is_good'] = $is_good_way;

                return $good_way;
            }
        }
        if ($dif_x !== 0) {
            $key = $start_cell->x + $dif_x . ':' . $start_cell->y;
            $new_cell = $this->cells[$key] ?? null;
            //$new_cell = $cells->where('x',$start_cell->x+$dif_x)->where('y',$start_cell->y)->first();
            $good_way = $this->chooseWay($new_cell, $finish_cell, $way, true, false);
            if (!$best_way) {
                $best_way = $good_way;
            } elseif ($good_way and (count($best_way['way']) > count($good_way['way']))) {
                $best_way = $good_way;
            }
            if ($good_way and $good_way['way_is_good']) {
                // Log::info(print_r(Arr::pluck($way,'id'),1));
                $good_way['way_is_good'] = $is_good_way;

                return $good_way;
            }
        }

        foreach ($directions as $direction) {
            $dif_y = $direction['y'];
            $dif_x = $direction['x'];
            $key = $start_cell->x + $dif_x . ':' . $start_cell->y + $dif_y;
            $new_cell = $this->cells[$key] ?? null;
            //if (!$new_cell) continue;
            //$new_cell = $cells->where('x',$start_cell->x+$dif_x)->where('y',$start_cell->y+$dif_y)->first();

            $good_way = $this->chooseWay($new_cell, $finish_cell, $way, false, false);
            if (!$best_way) {
                $best_way = $good_way;
            } elseif ($good_way and (count($best_way['way']) > count($good_way['way']))) {
                $best_way = $good_way;
            }

        }
        //Log::info(print_r(Arr::pluck($way,'id'),1));
        return $best_way;
    }
}
