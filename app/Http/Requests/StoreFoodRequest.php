<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class StoreFoodRequest extends FormRequest {
    public function authorize(): bool { return true; }
    public function rules(): array { return ['name' => 'required|string|max:255', 'category' => 'required|in:Makanan,Minuman,Cemilan', 'price' => 'required|numeric|min:0']; }
}
