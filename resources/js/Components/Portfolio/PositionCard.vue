<script setup>

const props = defineProps({
    position: {
        type: Object,
        required: true,
    },
})


const formatMoney = (value, currency = 'RUB') => {

    if(value === null || value === undefined){
        return '—'
    }

    return new Intl.NumberFormat('ru-RU',{
        style:'currency',
        currency: currency.toUpperCase(),
        maximumFractionDigits:2
    }).format(Number(value))
}


const formatNumber = (value)=>{

    if(value === null || value === undefined){
        return '—'
    }

    return new Intl.NumberFormat('ru-RU',{
        maximumFractionDigits:8
    }).format(Number(value))

}


const yieldClass = (value)=>{

    const number = Number(value)

    if(number > 0){
        return 'text-emerald-400'
    }

    if(number < 0){
        return 'text-red-400'
    }

    return 'text-slate-300'
}


const formatYield = (value, currency)=>{

    const number = Number(value)

    return `${number > 0 ? '+' : ''}${formatMoney(number,currency)}`
}

</script>


<template>

    <div
        class="
    rounded-xl
    border
    border-dark-border
    bg-dark-card
    p-5
    transition
    hover:border-slate-500
    "
    >


        <div class="flex justify-between">

            <div class="flex items-center gap-3">

                <img
                    v-if="position.asset.logo_url"
                    :src="position.asset.logo_url"
                    class="h-10 w-10 rounded-lg bg-white object-contain p-1"
                />

                <div>
                    <div class="font-semibold text-white">
                        {{ position.asset.name }}
                    </div>

                    <div class="text-xs text-slate-400">
                        {{ position.asset.ticker }}
                    </div>
                </div>

            </div>


            <div class="text-right">

                <div class="text-lg font-semibold text-white">
                    {{formatMoney(
                    position.current_value,
                    position.currency
                )}}
                </div>


                <div
                    class="text-sm font-medium"
                    :class="yieldClass(position.expected_yield)"
                >
                    {{formatYield(
                    position.expected_yield,
                    position.currency
                )}}
                </div>

            </div>

        </div>



        <div
            class="mt-5 grid grid-cols-3 gap-4"
            v-if="position.average_position_price > 1"
        >


            <div>
                <div class="text-xs text-slate-400">
                    Количество
                </div>

                <div class="mt-1 text-sm text-white">
                    {{formatNumber(position.quantity)}}
                </div>
            </div>



            <div>

                <div class="text-xs text-slate-400">
                    Средняя цена
                </div>

                <div class="mt-1 text-sm text-white">
                    {{
                        formatMoney(
                            position.average_position_price,
                            position.currency
                        )
                    }}
                </div>

            </div>



            <div>

                <div class="text-xs text-slate-400">
                    Сейчас
                </div>

                <div class="mt-1 text-sm text-white">
                    {{
                        formatMoney(
                            position.current_price,
                            position.currency
                        )
                    }}
                </div>

            </div>


        </div>


    </div>

</template>
