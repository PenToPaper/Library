import { getCSRFToken } from '@/helpers/csrfHelper';
import { Book } from '@/types/book';

export const checkOutBook = async (book: Book): Promise<boolean> => {
    try {
        const response = await fetch('/borrow', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCSRFToken(),
            },
            body: JSON.stringify({
                book_id: book.id,
            }),
        });

        if (!response.ok) {
            const errorData = await response.json();
            console.error('Error checking out book:', errorData);
            return false;
        }

        return true;
    } catch (error) {
        console.error('Failed to check out book:', error);
    }
    return false;
};

export const markBookReturned = async (book: Book): Promise<boolean> => {
    try {
        const response = await fetch(`/borrow/return`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCSRFToken(),
            },
            body: JSON.stringify({
                book_id: book.id,
            }),
        });

        if (!response.ok) {
            const errorData = await response.json();
            console.error('Error marking book returned:', errorData);
            return false;
        }

        return true;
    } catch (error) {
        console.error('Failed to mark book returned:', error);
    }
    return false;
};

export const deleteBook = async (book: Book): Promise<boolean> => {
    try {
        const response = await fetch(`/books/${book.id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': getCSRFToken(),
                'Content-Type': 'application/json',
            },
        });

        if (!response.ok) {
            const errorData = await response.json();
            console.error('Failed to delete book:', errorData);
            return false;
        }

        return true;
    } catch (error) {
        console.error('Error deleting book:', error);
    }
    return false;
};

export const getAllBooks = async (): Promise<Book[]> => {
    try {
        const response = await fetch('/books');
        if (!response.ok) return [];
        return await response.json();
    } catch (error) {
        console.error('Failed to fetch books:', error);
    }
    return [];
};

export const submitNewBook = async (book: Book): Promise<boolean> => {
    try {
        const response = await fetch('/books', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCSRFToken(),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(book),
        });

        if (!response.ok) {
            const errorData = await response.json();
            console.error('Failed to add book:', errorData);
            return false;
        }

        return true;
    } catch (error) {
        console.error('Error adding book:', error);
    }
    return false;
};

export const editBook = async (book: Book): Promise<boolean> => {
    try {
        const response = await fetch(`/books/${book.id}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': getCSRFToken(),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(book),
        });

        if (!response.ok) {
            const errorData = await response.json();
            console.error('Failed to update book:', errorData);
            return false;
        }

        return true;
    } catch (error) {
        console.error('Error updating book:', error);
    }
    return false;
};

export const submitOrUpdateBook = async (book: Book) => {
    if (book.id <= 0) {
        return submitNewBook(book);
    } else {
        return editBook(book);
    }
};
