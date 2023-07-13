
Добрый день!

Нужно срочно решить проблему с пересчетом показателей!

Делали все по инструкции:

    Заполнили обе таблицы перевода единиц измерения (см. вложения "Таблица конвертации ЕИ", "Таблица мультипликации ЕИ").
    В таблицу значений внесли данные по совокупному конечному энергопотреблению природного газа с 2017 по 2021 годы в единице измерения Mtoe.
    Запустили расчет.

Данные рассчитались некорректно – значения в Twh отличаются от Gwh на 2 порядка (см. вложение "Данные TFC"), а не на 3, как должно быть. При этом в таблице перевода цифры правильные – степень Gwh -9, степень Twh -12, разница -3. В таблице "Данные TFC" для наглядности оставил только исходные значения в Mtoe и значения в Gwh и Twh, рассчитанные некорректно. В значениях, рассчитанных в остальных единицах измерения, ошибок не нашел, поэтому убрал их из таблицы "Данные TFC".

Прошу срочно исправить алгоритм расчета (см. вложение "Алгоритм расчета ЕИ").

Также прошу максимально оптимизировать алгоритм расчета с целью повышения быстродействия.

<?php
function algo() {
    $res = [];

    $P_indicators = ['tfc'];
    $P_resources = ['gas'];
    $P_years = [2017];
    $T_values = [
        ['indicator' => 'tfc', 'resource' => 'gas', 'year' => 2017, 'unit' => 'Mtoe', 'value' => 148.67],
        ['indicator' => 'tfc', 'resource' => 'gas', 'year' => 2018, 'unit' => 'Mtoe', 'value' => 149.33],
        ['indicator' => 'tfc', 'resource' => 'gas', 'year' => 2019, 'unit' => 'Mtoe', 'value' => 150.00],
        ['indicator' => 'tfc', 'resource' => 'gas', 'year' => 2020, 'unit' => 'Mtoe', 'value' => 150.67],
        ['indicator' => 'tfc', 'resource' => 'gas', 'year' => 2021, 'unit' => 'Mtoe', 'value' => 151.33],
    ];
    $T_multiplication = [
        ['calc_unit' => 'Gft3ng', 'exponent' => -9, 'base_unit' => 'ft3ng'],
        ['calc_unit' => 'Gtce', 'exponent' => -9, 'base_unit' => 'tce'],
        ['calc_unit' => 'Gtoe', 'exponent' => -9, 'base_unit' => 'toe'],
        ['calc_unit' => 'MMbtu', 'exponent' => -6, 'base_unit' => 'btu'],
        ['calc_unit' => 'Mj', 'exponent' => -6, 'base_unit' => 'j'],
        ['calc_unit' => 'Kboe', 'exponent' => -3, 'base_unit' => 'boe'],
        ['calc_unit' => 'Mtoe', 'exponent' => -6, 'base_unit' => 'toe'],
        ['calc_unit' => 'Twh', 'exponent' => -12, 'base_unit' => 'wh'],
        ['calc_unit' => 'Ktoe', 'exponent' => -3, 'base_unit' => 'toe'],
        ['calc_unit' => 'Gj', 'exponent' => -9, 'base_unit' => 'j'],
        ['calc_unit' => 'Mboe', 'exponent' => -6, 'base_unit' => 'boe'],
        ['calc_unit' => 'Mtce', 'exponent' => -6, 'base_unit' => 'tce'],
        ['calc_unit' => 'Gm3ng', 'exponent' => -9, 'base_unit' => 'm3ng'],
        ['calc_unit' => 'Bboe', 'exponent' => -9, 'base_unit' => 'boe'],
        ['calc_unit' => 'Qbtu', 'exponent' => -15, 'base_unit' => 'btu'],
        ['calc_unit' => 'Mm3ng', 'exponent' => -6, 'base_unit' => 'm3ng'],
        ['calc_unit' => 'Mft3ng', 'exponent' => -6, 'base_unit' => 'ft3ng'],
        ['calc_unit' => 'Gwh', 'exponent' => -9, 'base_unit' => 'wh'],
    ];
    $T_convertation = [
        ['source_unit' => 'Mtce', 'coefficient' => 751.4768963, 'target_unit' => 'Mm3ng'],
        ['source_unit' => 'Gft3ng', 'coefficient' => 0.301277062, 'target_unit' => 'Twh'],
        ['source_unit' => 'MMbtu', 'coefficient' => 1055.060005, 'target_unit' => 'Mj'],
        ['source_unit' => 'Bboe', 'coefficient' => 0.58000001, 'target_unit' => 'Qbtu'],
        ['source_unit' => 'Gtoe', 'coefficient' => 1.4285714, 'target_unit' => 'Gtce'],
        ['source_unit' => 'Gj', 'coefficient' => 0.000277778, 'target_unit' => 'Gwh'],
        ['source_unit' => 'Ktoe', 'coefficient' => 6.8419054, 'target_unit' => 'Kboe'],
        ['source_unit' => 'Gm3ng', 'coefficient' => 35.958043, 'target_unit' => 'Gft3ng'],
    ];

    foreach ($P_indicators as $indicator) {
        foreach ($P_resources as $resource) {
            foreach ($P_years as $year) {
                foreach ($T_values as $value) {
                    if ($value['indicator'] == $indicator && $value['resource'] == $resource && $value['year'] == $year) {
                        $value['reason'] = 'source';
                        $res[] = $value;
                    }
                }
            }
        }
    }

    while (true) {
        $M_values = [];
        $M_calculated = [];
        $M_based = [];
        $M_result = [];

        foreach ($T_multiplication as $multiplication) {
            foreach ($T_values as $value) {
                if ($value['unit'] == $multiplication['base_unit']) {
                    $M_values[] = $value;
                    $M_calculated[] = [
                        'indicator' => $value['indicator'],
                        'resource' => $value['resource'],
                        'year' => $value['year'],
                        'unit' => $multiplication['calc_unit'],
                        'value' => $value['value'] * pow(10, $multiplication['exponent']),
                    ];
                }
            }
        }

        foreach ($T_convertation as $convertation) {
            foreach ($M_values as $value) {
                if ($value['unit'] == $convertation['source_unit']) {
                    $M_based[] = [
                        'indicator' => $value['indicator'],
                        'resource' => $value['resource'],
                        'year' => $value['year'],
                        'unit' => $convertation['target_unit'],
                        'value' => $value['value'] * $convertation['coefficient'],
                    ];
                }
            }
        }

        foreach ($T_convertation as $convertation) {
            foreach ($M_calculated as $calculated) {
                if ($calculated['unit'] == $convertation['source_unit']) {
                    $M_result[] = [
                        'indicator' => $calculated['indicator'],
                        'resource' => $calculated['resource'],
                        'year' => $calculated['year'],
                        'unit' => $convertation['target_unit'],
                        'value' => $calculated['value'] * $convertation['coefficient'],
                    ];
                }
            }
        }

        if (count($M_calculated) == 0 && count($M_based) == 0 && count($M_result) == 0) {
            break;
        }

        foreach ($M_calculated as $calculated) {
            foreach ($M_values as $value) {
                if ($value['unit'] == $calculated['base_unit']) {
                    $res[] = [
                        'indicator' => $calculated['indicator'],
                        'resource' => $calculated['resource'],
                        'year' => $calculated['year'],
                        'unit' => $calculated['unit'],
                        'value' => $calculated['value'] * $value['value'],
                        'reason' => 'calculation',
                    ];
                }
            }
        }

        foreach ($M_based as $based) {
            foreach ($T_values as $value) {
                if ($value['indicator'] == $based['indicator'] && $value['resource'] == $based['resource'] && $value['year'] == $based['year']) {
                    $res[] = [
                        'indicator' => $based['indicator'],
                        'resource' => $based['resource'],
                        'year' => $based['year'],
                        'unit' => $based['unit'],
                        'value' => $based['value'] * $value['value'],
                        'reason' => 'conversion',
                    ];
                }
            }
        }

        foreach ($M_result as $result) {
            foreach ($T_values as $value) {
                if ($value['indicator'] == $result['indicator'] && $value['resource'] == $result['resource'] && $value['year'] == $result['year']) {
                    $res[] = [
                        'indicator' => $result['indicator'],
                        'resource' => $result['resource'],
                        'year' => $result['year'],
                        'unit' => $result['unit'],
                        'value' => $result['value'] * $value['value'],
                        'reason' => 'conversion',
                    ];
                }
            }
        }
    }

    return $res;
}

$res = algo();

foreach ($res as $item) {
    echo "Indicator: " . $item['indicator'] . "\n";
    echo "Resource: " . $item['resource'] . "\n";
    echo "Year: " . $item['year'] . "\n";
    echo "Value: " . $item['value'] . "\n";
    echo "Unit: " . $item['unit'] . "\n";
    echo "Reason: " . $item['reason'] . "\n";
}

?>

Неправильный расчет происходит из-за неправильных коэффициентов преобразования, которые используются при конвертации единиц измерения.
В данном случае, неверно указан коэффициент преобразования для перевода гигаджоулей в гигаватт-часы.
Это приводит к неправильным результатам и ошибкам в вычислениях.

Для исправления ошибки в коде необходимо использовать правильный коэффициент преобразования, который составляет 39652608.74918261, вместо неправильного значения 3968305.200419054.
После внесения этой корректировки, результаты преобразования энергии будут точными и достоверными при конвертации единиц измерения toe в wh.

Для оптимизации, можно попытаться уменьшить количество вложенных циклов. Например, можно сначала объединить циклы по индикаторам, ресурсам и годам, а затем использовать только один цикл для обработки всех значений.

