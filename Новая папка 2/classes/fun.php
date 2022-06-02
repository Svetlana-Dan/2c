<?php 
include_once 'database.php';

function e($value)
{
    return htmlspecialchars($value);
}

function convertIndex(string $search, array $collect) : int
{
  $count = 1;

  foreach ($collect as $key => $value)
  {
    if (strcmp($value["name"], $search))
    {
      $count += 1;
    }
    else
    {
      break;
    }
  }

  if ($count > count($collect))
  {
    return 0;
  }

  return $count;
}