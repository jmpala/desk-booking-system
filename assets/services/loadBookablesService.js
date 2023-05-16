'use strict';

import axios from 'axios';
import { extractDateFromDateIsoString } from '@/js/src/components/seatmap/utils/utils';

export async function getOfficeStateByDate(date: Date) {
    const temp = new Date();
    let res = null;
    try {
        res = await axios.get('/api/booking/overview/' + extractDateFromDateIsoString(temp));
    } catch (e) {
        console.error(e);
    }
    console.log(res);
}

export default {
    getOfficeStateByDate
};
