<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table            = 'settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['key', 'value', 'group', 'description'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get setting value by key
     */
    public function getVal(string $key, $default = null)
    {
        $setting = $this->where('key', $key)->first();
        return $setting ? $setting['value'] : $default;
    }

    /**
     * Get reward percentages as an array
     */
    public function getRewardPercentages(): array
    {
        $percentages = [];
        for ($i = 1; $i <= 8; $i++) {
            $percentages[] = (float) $this->getVal("reward_level_{$i}", 0);
        }
        return $percentages;
    }

    /**
     * Update setting by key
     */
    public function setVal(string $key, $value)
    {
        return $this->where('key', $key)->set(['value' => $value])->update();
    }
}
