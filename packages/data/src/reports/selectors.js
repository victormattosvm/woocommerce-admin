/**
 * Internal dependencies
 */
import { getResourceName } from '../utils';

export const getReportItemsError = ( state, endpoint, query ) => {
	const resourceName = getResourceName( endpoint, query );
	return state.itemErrors[ resourceName ] || false;
};

export const getReportItems = ( state, endpoint, query ) => {
	const resourceName = getResourceName( endpoint, query );
	return state.items[ resourceName ] || {};
};

export const getReportStats = ( state, endpoint, query ) => {
	const resourceName = getResourceName( endpoint, query );
	return state.stats[ resourceName ] || {};
};

export const getReportStatsError = ( state, endpoint, query ) => {
	const resourceName = getResourceName( endpoint, query );
	return state.statErrors[ resourceName ] || false;
};
