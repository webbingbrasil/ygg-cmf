<?php


namespace Ygg\Actions;


interface HasForm
{
    public function form(): array;
    public function formLayout(): ?array;
}