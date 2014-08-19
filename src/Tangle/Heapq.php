<?php

namespace Tangle;

class Heapq
{
    public $heap;
    public function __construct(array $seq = array())
    {
        $this->heap = array();
        foreach($seq as $v) {
            $this->heappush($v);
        }
    }

    public function first()
    {
        if(count($this->heap) > 0) {
            return $this->heap[0];
        } else {
            return false;
        }
    }

    public function heappush($item)
    {
        array_push($this->heap, $item);
        $this->_siftdown(0, count($this->heap)-1);
    }
    
    public function heappop()
    {
        $lastelt = array_pop($this->heap);
        if(!empty($this->heap)) {
            $returnitem = $this->heap[0];
            $this->heap[0] = $lastelt;
            $this->_siftup(0);
        } else {
            $returnitem = $lastelt;
        }
        return $returnitem;
    }
    private function _siftdown($startpos, $pos)
    {
        $newitem = $this->heap[$pos];
        while($pos > $startpos) {
            $parentpos = ($pos - 1) >> 1;
            $parent = $this->heap[$parentpos];
            if($newitem < $parent){
                $this->heap[$pos] = $parent;
                $pos = $parentpos;
                continue;
            }
            break;
        }
        $this->heap[$pos] = $newitem;
    }
    private function _siftup($pos)
    {
        $endpos = count($this->heap);
        $startpos = $pos;
        $newitem = $this->heap[$pos];
        $childpos = 2*$pos + 1;
        while($childpos < $endpos) {
            $rightpos = $childpos + 1;
                if($rightpos < $endpos && $this->heap[$childpos] >= $this->heap[$rightpos]) {
                    $childpos = $rightpos;
                }
            $this->heap[$pos] = $this->heap[$childpos];
            $pos = $childpos;
            $childpos = 2*$pos + 1;
        }
        $this->heap[$pos] = $newitem;
        $this->_siftdown($startpos, $pos);
    }
}
#$seq = array(3, 7, 2, 3, 1, 6);
#$hq = new Heapq($seq);
#echo $hq->first();
#echo $hq->heappop();
#echo $hq->heappop();
#echo $hq->first();
#echo $hq->heappop();
#echo $hq->heappush(1);
#echo $hq->heappop();
