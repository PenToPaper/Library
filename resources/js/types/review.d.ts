export interface Review {
    id: number;
    book_id: number;
    user_id: number;
    rating: number;
    message: string;
    created_at: string;
    updated_at: string;
}
