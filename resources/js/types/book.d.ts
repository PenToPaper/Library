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
}
