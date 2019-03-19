<?php

namespace App\Http\Requests;

class UserAddressRequest extends Request
{
    public function rules()
    {
        return [
            'province'      => 'required',
            'city'          => 'required',
            'district'      => 'required',
            'address'       => 'required',
            'zip'           => 'required',
            'contact_name'  => 'required',
            'contact_phone' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'province'      => 'State',
            'city'          => 'City',
            'district'      => 'District',
            'address'       => 'Address',
            'zip'           => 'Postcode',
            'contact_name'  => 'Contact Name',
            'contact_phone' => 'Mobile No.:',
        ];
    }
}