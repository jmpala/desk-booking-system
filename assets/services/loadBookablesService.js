'use strict';

import axios from 'axios';
import { extractDateFromDateIsoString } from '@/js/src/components/seatmap/utils/utils';

export async function getOfficeStateByDate(date: Date): Promise<any> {
    let res = null;
    try {
        res = await axios.get('/api/booking/overview/' + extractDateFromDateIsoString(date));
    } catch (e) {
        console.error(e);
    }
    return res;
}

export default {
    getOfficeStateByDate
};
