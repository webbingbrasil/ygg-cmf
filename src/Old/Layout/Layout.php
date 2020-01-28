<?php


namespace Ygg\Old\Layout;

/**
 * Interface Layout
 * @package Ygg\Old\Layout
 */
interface Layout
{
    public function setRowColumnClass(string $rowColumnClass): void;
    public function toArray(): array;
}