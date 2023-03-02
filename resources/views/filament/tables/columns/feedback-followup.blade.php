<hr class="mt-6"/>

@foreach($getRecord()->feedbackFollowups as $followup)
    <div
        {{
            $attributes
                ->merge($getExtraAttributes())
                ->class([
                    'my-6 pl-8 d-flex',
                    'dark:bg-gray-900' => config('forms.dark_mode'),
                ])
        }}
    >

        <div class="font-bold">
            Followup: {{ (new \Carbon\Carbon($followup->created_at))->toDateString() }}
        </div>
        <x-tables::columns.layout
            :components="[
            \Filament\Tables\Columns\TextColumn::make('comments'),
        ]"
            :record="$followup"
            :record-key="$followup->id"
        />

    </div>
    <hr/>
@endforeach
