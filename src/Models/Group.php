<?php

namespace Models;

use Core\Model;
use Database\Migrations\CreateGroupsTable;

class Group extends Model implements Models
{
    public static $entity = "tb_group";

    /** @var array */
    private $required = [ "name" ];

    public function load(int $id, string $columns = "*", bool $msgDb = false)
    {
        $load = $this->read("SELECT {$columns} FROM " . self::$entity . " WHERE id=:id", "id={$id}", $msgDb);

        if($this->fail || !$load->rowCount()) {
            $this->message = ($msgDb ? $this->fail->errorInfo[2] : "<span class='warning'>Not Found Informed ID Group</span>");
            return null;
        }

        return $load->fetchObject(__CLASS__);
    }

    public function find(string $search, string $columns = "*")
    {
        if(filter_var($search, FILTER_SANITIZE_STRIPPED)) {
            $find = $this->read("SELECT {$columns} FROM " . self::$entity . " WHERE name=:name ", "name={$search}");
        }

        if($this->fail || !$find->rowCount()) {
            $this->message = "<span class='warning'>Not found group</span>";
            return null;
        }

        return $find->fetchObject(__CLASS__);
    }

    public function all(int $limit=30, int $offset=1, string $columns = "*", string $order = "id"): ?array
    {
        $all = $this->read("SELECT {$columns} FROM  "
            . self::$entity . " "
            . $this->order($order)
            . $this->limit(), "limit={$limit}&offset={$offset}");

        if($this->fail || !$all->rowCount()) {
            $this->message = "<span class='warning'>Your inquiry has not returned data</span>";
            return null;
        }

        return $all->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    public function save(): ?Group
    {
        if(!$this->required()) {
            return null;
        }

        /** Update */
        if(!empty($this->id)) {
            $groupId = $this->id;
            $group = $this->read("SELECT id FROM " . self::$entity . " WHERE name = :name AND id != :id",
                "name={$this->name}&id={$groupId}");
            if($group->rowCount()) {
                $this->message = "<span class='warning'>The Informed Group is already registered</span>";
                return null;
            }

            $this->update(self::$entity, $this->safe(), "id = :id", "id={$groupId}");
            if($this->fail()) {
                $this->message = "<span class='danger'>Error updating, verify the data</span>";
                return null;
            }

            $this->message = "<span class='success'>Data updated successfully</span>";
        }

        /** Create */
        if(empty($this->id)) {
            if($this->find($this->name)) {
                $this->message = "<span class='warning'>The Informed Group is already registered</span>";
                return null;
            }
            $groupId = $this->create(self::$entity, $this->safe());
            if($this->fail()) {
                $this->message = "<span class='danger'>Error to Register, Check the data</span>";
                return null;
            }
            $this->message = "<span class='success'>Registration successfully</span>";
        }
        $this->data = $this->read("SELECT * FROM " . self::$entity . " WHERE id=:id", "id={$groupId}")->fetch();

        return $this;
    }

    public function destroy()
    {
        if(!empty($this->id)) {
            $this->delete(self::$entity, "id=:id", "id={$this->id}");
        }

        if($this->fail()) {
            $this->message = "<span class='danger'>Could not remove the group</span>";
            return null;
        }
        $this->message = "<span class='success'>Group was successfully removed</span>";
        $this->data = null;

        return $this;
    }

    public function required(): bool
    {
        foreach($this->required as $field) {
            if(empty(trim($this->$field))) {
                $this->message = "<span class='warning'>The field {$field} is required</span>";
                return false;
            }
        }

        return true;
    }

    public function createThisTable()
    {
        $sql = (new CreateGroupsTable())->up(self::$entity);
        return $this->createTable($sql);
    }

    public function dropThisTable()
    {
        $sql = (new CreateGroupsTable())->down(self::$entity);
        return $this->dropTable($sql);
    }

}
