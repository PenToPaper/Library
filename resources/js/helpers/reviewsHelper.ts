import { getCSRFToken } from '@/helpers/csrfHelper';
import { Review } from '@/types/review';

export const updateReview = async (review: Review) => {
    try {
        const response = await fetch(`/review/${review.id}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': getCSRFToken(),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(review),
        });

        if (!response.ok) {
            const errorData = await response.json();
            console.error('Failed to update review:', errorData);
            return false;
        }

        return true;
    } catch (error) {
        console.error('Error updating review:', error);
    }
    return false;
};

export const insertReview = async (review: Review) => {
    try {
        const response = await fetch(`/review`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCSRFToken(),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(review),
        });

        if (!response.ok) {
            const errorData = await response.json();
            console.error('Failed to add review:', errorData);
            return false;
        }

        return true;
    } catch (error) {
        console.error('Error adding review:', error);
    }
    return false;
};

export const submitOrUpdateReview = async (review: Review) => {
    if (review.id <= 0) {
        return insertReview(review);
    } else {
        return updateReview(review);
    }
};

export const deleteReview = async (review: Review) => {
    try {
        const response = await fetch(`/review/${review.id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': getCSRFToken(),
                'Content-Type': 'application/json',
            },
        });

        if (!response.ok) {
            const errorData = await response.json();
            console.error('Failed to delete review:', errorData);
            return false;
        }

        return true;
    } catch (error) {
        console.error('Error deleting review:', error);
    }
    return false;
};
