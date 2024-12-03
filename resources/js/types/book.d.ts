export interface Review {
    id: number;
    book_id: number;
    user_id: number;
    rating: number;
    message: string;
    created_at: string;
    updated_at: string;
}

export interface Book {
    id: number;
    title: string;
    author: string;
    description?: string | null;
    cover_image: string;
    publisher: string;
    publication_date: string;
    category: string;
    isbn: string;
    page_count: number;
    created_at: string;
    updated_at: string;
    is_available: boolean;
    average_rating: number | null;
    review_count: number;
    reviews: Review[];
}
