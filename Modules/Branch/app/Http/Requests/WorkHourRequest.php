<?php

namespace Modules\Branch\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Branch\Models\Branch;

class WorkHourRequest extends FormRequest
{
    public function authorize(): bool
    {
        $branch = $this->route('branch');
        $branch = $branch instanceof Branch ? $branch : Branch::find($branch);

        return $branch ? $this->user('admin')?->can('update', $branch) : false;
    }

    public function rules(): array
    {
        return [
            'hours' => ['required', 'array', 'size:7'],
            'hours.*.day' => ['required', 'string', 'in:saturday,sunday,monday,tuesday,wednesday,thursday,friday'],
            'hours.*.is_closed' => ['nullable', 'boolean'],
            'hours.*.from' => ['nullable', 'date_format:H:i'],
            'hours.*.to' => ['nullable', 'date_format:H:i'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $hours = $this->input('hours', []);

            foreach ($hours as $index => $item) {
                $isClosed = (bool) ($item['is_closed'] ?? false);
                $from = $item['from'] ?? null;
                $to = $item['to'] ?? null;

                if (! $isClosed) {
                    if (! $from || ! $to) {
                        $validator->errors()->add("hours.$index.from", __('validation.required', ['attribute' => __('dashboard/branches.working_hours')]));
                        continue;
                    }

                    if ($from >= $to) {
                        $validator->errors()->add("hours.$index.to", __('dashboard/branches.work_hour_to_must_be_after_from'));
                    }
                }
            }
        });
    }
}
