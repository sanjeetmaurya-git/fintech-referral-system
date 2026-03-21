<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RechargeOperatorModel;

class RechargeController extends BaseController
{
    public function save()
    {
        $validationRules = [
            'name' => 'required|string',
            'id' => 'permit_empty|integer',
            'tier_1_max' => 'required|integer',
            'tier_1_coins' => 'required|integer',
            'tier_2_max' => 'required|integer',
            'tier_2_coins' => 'required|integer',
            'tier_3_max' => 'required|integer',
            'tier_3_coins' => 'required|integer',
            'tier_4_max' => 'required|integer',
            'tier_4_coins' => 'required|integer',
        ];

        // Add file validation rule only if a file is uploaded
        if ($this->request->getFile('logo_url')->isValid()) {
            $validationRules['logo_url'] = [
                'label' => 'Operator Logo',
                'rules' => 'uploaded[logo_url]|is_image[logo_url]|max_size[logo_url,2048]|ext_in[logo_url,png,jpg,jpeg,svg,webp]',
            ];
        }

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $operatorModel = new RechargeOperatorModel();
        $id = $this->request->getPost('id');
        $data = [
            'name' => $this->request->getPost('name'),
            'tier_1_max' => $this->request->getPost('tier_1_max'),
            'tier_1_coins' => $this->request->getPost('tier_1_coins'),
            'tier_2_max' => $this->request->getPost('tier_2_max'),
            'tier_2_coins' => $this->request->getPost('tier_2_coins'),
            'tier_3_max' => $this->request->getPost('tier_3_max'),
            'tier_3_coins' => $this->request->getPost('tier_3_coins'),
            'tier_4_max' => $this->request->getPost('tier_4_max'),
            'tier_4_coins' => $this->request->getPost('tier_4_coins'),
        ];

        if ($id) {
            $data['id'] = $id;
        }

        $logoFile = $this->request->getFile('logo_url');

        if ($logoFile->isValid() && !$logoFile->hasMoved()) {
            if ($id) {
                $currentLogo = $this->request->getPost('current_logo');
                if ($currentLogo && file_exists(FCPATH . $currentLogo)) {
                    @unlink(FCPATH . $currentLogo);
                }
            }
            $newName = $logoFile->getRandomName();
            $logoFile->move(FCPATH . 'images/operators', $newName);
            $data['logo_url'] = 'images/operators/' . $newName;
        }

        $operatorModel->save($data);

        return redirect()->to('admin/services/recharge')->with('success', 'Operator saved successfully.');
    }
}