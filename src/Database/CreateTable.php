<?php

namespace Database;

interface CreateTable
{
    public function __construct();
    public function up(string $entity);
    public function down(string $entity);
}
