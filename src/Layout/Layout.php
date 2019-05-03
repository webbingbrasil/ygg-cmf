<?php


namespace Ygg\Layout;

/**
 * Interface Layout
 * @package Ygg\Layout
 */
interface Layout
{
    public function setRowColumnClass(string $rowColumnClass): void;
    public function toArray(): array;
}